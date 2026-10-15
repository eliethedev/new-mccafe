<?php

class HomeController extends Controller {

    public function index() {
        // CafeCat featured items (from DB)
        $featuredDb = Product::getFeaturedItems(8);
        // CafeCat best sellers (flagged)
        $bestSellerDb = Product::getFlaggedBestSellers(8);

        $featuredProducts = [];
        foreach ($featuredDb as $p) {
            $featuredProducts[] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'price' => (float)$p['price'],
                'description' => $p['description'] ?: 'Crafted for your cravings.',
                'image' => $p['image'] ? 'products/' . $p['image'] : 'cafecat/spanish-latte.jpg',
            ];
        }

        $bestSellers = [];
        foreach ($bestSellerDb as $p) {
            $bestSellers[] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'price' => (float)$p['price'],
                'description' => $p['description'] ?: 'Best loved by our customers.',
                'image' => $p['image'] ? 'products/' . $p['image'] : 'cafecat/spanish-latte.jpg',
            ];
        }

        $dbCategories = Product::getCategories();
        $categories = [];
        foreach ($dbCategories as $c) {
            $categories[] = [
                'name' => $c['name'],
                'product_count' => (int) $c['product_count'],
                'image' => !empty($c['image']) ? 'categories/' . $c['image'] : null,
            ];
        }
        if (empty($categories)) {
            $categories = [
                ['name' => 'Coffee', 'product_count' => 0, 'image' => 'cafecat/spanish-latte.jpg'],
            ];
        }

        return $this->view('home', [
            'featuredProducts' => $featuredProducts,
            'bestSellers' => $bestSellers,
            'categories' => $categories,
            'title' => 'cafecat — crafted for your cravings'
        ]);
    }
}
