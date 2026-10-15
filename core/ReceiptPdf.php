<?php

/**
 * ReceiptPdf — dependency-free PDF receipt/invoice generator.
 *
 * Builds a valid PDF 1.4 document with core Helvetica fonts only
 * (no embedding, no extensions required). Black & white, A4.
 *
 * Usage:
 *   $pdf = ReceiptPdf::render($order, $items, $refundInfo);
 *   // $pdf is raw PDF bytes
 */
class ReceiptPdf {
    const PAGE_W = 595;   // A4 width (pt)
    const PAGE_H = 842;   // A4 height (pt)
    const MARGIN = 48;

    private $pages = [];       // array of content-stream strings
    private $current = '';
    private $y = 0;

    // ------------------------------------------------------------------
    // Low-level helpers
    // ------------------------------------------------------------------

    /** Transliterate to WinAnsi-safe text (core fonts have no ₱ glyph). */
    public static function safeText($text) {
        $text = (string)$text;
        $map = [
            '₱' => 'P', '—' => '-', '–' => '-', '“' => '"', '”' => '"',
            '‘' => "'", '’' => "'", '•' => '-', '✓' => 'v', '✕' => 'x',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'á' => 'a', 'à' => 'a',
            'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n', 'ü' => 'u',
        ];
        $text = strtr($text, $map);
        // Drop any remaining non-WinAnsi chars.
        $text = preg_replace('/[^\x20-\x7E]/', '?', $text);
        return $text;
    }

    private static function esc($text) {
        $text = self::safeText($text);
        $text = str_replace('\\', '\\\\', $text);
        $text = str_replace('(', '\\(', $text);
        $text = str_replace(')', '\\)', $text);
        return $text;
    }

    private function newPage() {
        if ($this->current !== '') {
            $this->pages[] = $this->current;
        }
        $this->current = '';
        $this->y = self::PAGE_H - self::MARGIN;
    }

    private function ensureSpace($needed) {
        if ($this->y - $needed < self::MARGIN) {
            $this->newPage();
            $this->pageHeaderContinuation();
        }
    }

    private function filledRect($x, $yBottom, $w, $h, $gray) {
        $this->current .= sprintf("%.2F g %.2F %.2F %.2F %.2F re f\n", $gray, $x, $yBottom, $w, $h);
    }

    private function line($x1, $y1, $x2, $y2, $width = 0.75) {
        $this->current .= sprintf("%.2F w 0 G %.2F %.2F m %.2F %.2F l S\n", $width, $x1, $y1, $x2, $y2);
    }

    private function text($x, $y, $text, $size = 10, $bold = false, $gray = 0) {
        $font = $bold ? 'F2' : 'F1';
        $this->current .= sprintf(
            "BT /%s %.2F Tf %.2F g %.2F %.2F Td (%s) Tj ET\n",
            $font, $size, $gray, $x, $y, self::esc($text)
        );
    }

    private function rightText($rightX, $y, $text, $size = 10, $bold = false, $gray = 0) {
        $w = $this->textWidth($text, $size, $bold);
        $this->text($rightX - $w, $y, $text, $size, $bold, $gray);
    }

    /** Approximate width of core Helvetica (avg ~0.55em, bold ~0.58em). */
    private function textWidth($text, $size, $bold = false) {
        $text = self::safeText($text);
        $avg = $bold ? 0.58 : 0.55;
        // Narrow chars correction.
        $narrow = preg_match_all('/[ijl1.,:;!\' ]/', $text);
        $wide = preg_match_all('/[mwMW@#%]/', $text);
        $len = strlen($text) + $wide * 0.35 - $narrow * 0.22;
        return $len * $size * $avg;
    }

    // ------------------------------------------------------------------
    // Receipt layout
    // ------------------------------------------------------------------

    private function headerBand($order) {
        $this->filledRect(self::MARGIN, $this->y - 64, self::PAGE_W - 2 * self::MARGIN, 64, 0);
        $this->text(self::MARGIN + 14, $this->y - 30, 'cafecat', 22, true, 1);
        $this->text(self::MARGIN + 14, $this->y - 46, 'CRAFTED FOR YOUR CRAVINGS', 7, false, 1);
        $this->rightText(self::PAGE_W - self::MARGIN - 14, $this->y - 30, 'OFFICIAL RECEIPT', 13, true, 1);
        $this->rightText(
            self::PAGE_W - self::MARGIN - 14, $this->y - 46,
            '#' . ($order['order_number'] ?? 'N/A'), 9, false, 1
        );
        $this->y -= 64 + 18;
    }

    private function pageHeaderContinuation() {
        $this->text(self::MARGIN, $this->y, 'cafecat — Official Receipt (continued)', 9, true, 0);
        $this->y -= 16;
        $this->line(self::MARGIN, $this->y, self::PAGE_W - self::MARGIN, $this->y);
        $this->y -= 14;
    }

    private function metaBlock($order) {
        $left = self::MARGIN;
        $this->text($left, $this->y, 'Order: #' . ($order['order_number'] ?? 'N/A'), 11, true); $this->y -= 15;
        $date = !empty($order['created_at']) ? date('F d, Y h:i A', strtotime($order['created_at'])) : 'N/A';
        $this->text($left, $this->y, 'Date: ' . $date, 9, false, 0.25); $this->y -= 13;
        $this->text($left, $this->y, 'Status: ' . ucfirst($order['status'] ?? 'N/A'), 9, false, 0.25); $this->y -= 20;

        $this->text($left, $this->y, 'Billed to:', 9, true); $this->y -= 14;
        $this->text($left, $this->y, $order['customer_name'] ?? 'N/A', 10, false); $this->y -= 14;
        foreach (['customer_address', 'customer_phone', 'customer_email'] as $field) {
            if (!empty($order[$field])) {
                $this->ensureSpace(14);
                $this->text($left, $this->y, $order[$field], 9, false, 0.25);
                $this->y -= 13;
            }
        }
        $this->y -= 8;
        $this->line(self::MARGIN, $this->y, self::PAGE_W - self::MARGIN, $this->y);
        $this->y -= 16;
    }

    private function itemsTable($items) {
        $left = self::MARGIN;
        $right = self::PAGE_W - self::MARGIN;
        // Column x-positions: Item | Qty | Unit | Amount
        $cQty = $right - 170;
        $cUnit = $right - 110;
        $cAmt = $right;

        $this->ensureSpace(30);
        $this->filledRect($left, $this->y - 16, $right - $left, 20, 0);
        $this->text($left + 6, $this->y - 13, 'ITEM', 9, true, 1);
        $this->text($cQty, $this->y - 13, 'QTY', 9, true, 1);
        $this->text($cUnit, $this->y - 13, 'UNIT PRICE', 9, true, 1);
        $this->rightText($cAmt - 6, $this->y - 13, 'AMOUNT', 9, true, 1);
        $this->y -= 16;

        $stripe = false;
        if (empty($items)) {
            $this->ensureSpace(20);
            $this->text($left + 6, $this->y - 4, 'No items.', 9, false, 0.4);
            $this->y -= 18;
        }
        foreach ((array)$items as $item) {
            $this->ensureSpace(20);
            if ($stripe) {
                $this->filledRect($left, $this->y - 16, $right - $left, 20, 0.94);
            }
            $stripe = !$stripe;
            $name = $item['product_name'] ?? 'Item';
            if (!empty($item['variant_name'])) {
                $name .= ' (' . $item['variant_name'] . ')';
            }
            if (strlen($name) > 52) {
                $name = substr($name, 0, 49) . '...';
            }
            $qty = (string)($item['quantity'] ?? 1);
            $unit = 'P' . number_format($item['unit_price'] ?? 0, 2);
            $amt = 'P' . number_format($item['total_price'] ?? 0, 2);
            $this->text($left + 6, $this->y - 4, $name, 9, false);
            $this->text($cQty, $this->y - 4, $qty, 9, false);
            $this->text($cUnit, $this->y - 4, $unit, 9, false);
            $this->rightText($cAmt - 6, $this->y - 4, $amt, 9, false);
            $this->y -= 20;
        }
        $this->line($left, $this->y + 4, $right, $this->y + 4);
        $this->y -= 12;
    }

    private function totalsBlock($order, $refundInfo) {
        $right = self::PAGE_W - self::MARGIN;
        $row = function ($label, $value, $bold = false) use ($right) {
            $this->ensureSpace(18);
            $this->rightText($right - 110, $this->y, $label, 9, $bold, $bold ? 0 : 0.25);
            $this->rightText($right, $this->y, $value, $bold ? 12 : 9, $bold);
            $this->y -= $bold ? 20 : 15;
        };

        $row('Subtotal:', 'P' . number_format($order['subtotal'] ?? 0, 2));
        if (!empty($order['discount_type']) && $order['discount_type'] !== 'none') {
            $dLabel = $order['discount_type'] === 'senior' ? 'Senior Discount (20%):' : 'PWD Discount (20%):';
            $row($dLabel, '-P' . number_format($order['discount_amount'] ?? 0, 2));
        }
        if (($order['tax_amount'] ?? 0) > 0) {
            $row('VAT (12%):', 'P' . number_format($order['tax_amount'], 2));
        } else {
            $row('VAT:', 'Exempt', false);
        }
        $this->line($right - 220, $this->y + 2, $right, $this->y + 2, 1);
        $this->y -= 4;
        $row('TOTAL:', 'P' . number_format($order['total_amount'] ?? 0, 2), true);

        if (!empty($refundInfo)) {
            $this->ensureSpace(20);
            $this->text(
                self::MARGIN, $this->y,
                'Refund: P' . number_format($refundInfo['refund_amount'] ?? 0, 2) .
                ' via ' . ucfirst($refundInfo['refund_method'] ?? '') .
                ' (' . ucfirst($refundInfo['refund_status'] ?? '') . ')',
                9, true
            );
            $this->y -= 16;
        }
        $this->y -= 6;
    }

    private function paymentBlock($order) {
        $this->ensureSpace(60);
        $this->text(self::MARGIN, $this->y, 'Payment Method: ' . ucfirst($order['payment_method'] ?? 'N/A'), 9, false);
        $this->y -= 14;
        $this->text(self::MARGIN, $this->y, 'Payment Status: ' . ucfirst($order['payment_status'] ?? 'N/A'), 9, false);
        $this->y -= 14;
        if (!empty($order['notes'])) {
            $notes = 'Notes: ' . preg_replace('/\s+/', ' ', $order['notes']);
            if (strlen($notes) > 110) {
                $notes = substr($notes, 0, 107) . '...';
            }
            $this->text(self::MARGIN, $this->y, $notes, 8, false, 0.4);
            $this->y -= 14;
        }
        $this->y -= 4;
        $this->line(self::MARGIN, $this->y, self::PAGE_W - self::MARGIN, $this->y);
        $this->y -= 16;
    }

    private function footerBlock($order) {
        $this->ensureSpace(70);
        $this->text(self::MARGIN, $this->y, 'Thank you for choosing CafeCat!', 10, true);
        $this->y -= 14;
        $this->text(self::MARGIN, $this->y, 'This serves as your official order receipt.', 8, false, 0.4);
        $this->y -= 12;
        $this->text(self::MARGIN, $this->y, 'Generated: ' . date('F d, Y h:i A'), 8, false, 0.4);
        $this->y -= 12;
        $this->text(self::MARGIN, $this->y, 'Feliciano Street, Bulacan, Philippines 3023 | info@cafecat.com', 8, false, 0.4);
    }

    // ------------------------------------------------------------------
    // Document assembly
    // ------------------------------------------------------------------

    private function build() {
        if ($this->current !== '') {
            $this->pages[] = $this->current;
            $this->current = '';
        }
        if (empty($this->pages)) {
            $this->pages[] = '';
        }

        $objects = [];
        // 1: catalog, 2: pages, 3: Helvetica, 4: Helvetica-Bold, then per-page page+content.
        $pageObjNums = [];
        $objNum = 5;
        foreach ($this->pages as $i => $stream) {
            $pageObjNums[$i] = $objNum;
            $objNum += 2;
        }

        $objects[1] = "<< /Type /Catalog /Pages 2 0 R >>";
        $kids = implode(' ', array_map(function ($n) { return "$n 0 R"; }, $pageObjNums));
        $objects[2] = "<< /Type /Pages /Kids [$kids] /Count " . count($this->pages) . " >>";
        $objects[3] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
        $objects[4] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>";

        foreach ($this->pages as $i => $stream) {
            $contentNum = $pageObjNums[$i] + 1;
            $objects[$pageObjNums[$i]] = "<< /Type /Page /Parent 2 0 R " .
                "/MediaBox [0 0 " . self::PAGE_W . " " . self::PAGE_H . "] " .
                "/Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> " .
                "/Contents $contentNum 0 R >>";
            $objects[$contentNum] = "<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "endstream";
        }

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [];
        $max = max(array_keys($objects));
        for ($i = 1; $i <= $max; $i++) {
            if (!isset($objects[$i])) {
                continue;
            }
            $offsets[$i] = strlen($pdf);
            $pdf .= "$i 0 obj\n" . $objects[$i] . "\nendobj\n";
        }
        $xrefPos = strlen($pdf);
        $pdf .= "xref\n0 " . ($max + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= $max; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i] ?? 0);
        }
        $pdf .= "trailer\n<< /Size " . ($max + 1) . " /Root 1 0 R >>\nstartxref\n$xrefPos\n%%EOF";
        return $pdf;
    }

    /**
     * Render a full receipt PDF.
     *
     * @param array $order  orders row (needs order_number, created_at, status,
     *                      customer_*, subtotal, discount_*, tax_amount, total_amount,
     *                      payment_method, payment_status, notes)
     * @param array $items  order_items rows (product_name, variant_name, quantity,
     *                      unit_price, total_price)
     * @param array|null $refundInfo latest refund row or null
     * @return string raw PDF bytes
     */
    public static function render($order, $items = [], $refundInfo = null) {
        $doc = new self();
        $doc->newPage();
        $doc->headerBand($order);
        $doc->metaBlock($order);
        $doc->itemsTable($items);
        $doc->totalsBlock($order, $refundInfo);
        $doc->paymentBlock($order);
        $doc->footerBlock($order);
        return $doc->build();
    }
}
