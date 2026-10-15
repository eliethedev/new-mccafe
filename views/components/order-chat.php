<?php
// Order chat partial — customer <-> rider <-> admin thread for one order.
// Requires: $chatOrderId. Works inside any Bootstrap layout.
$chatOrderId = $chatOrderId ?? null;
if (empty($chatOrderId)) {
    return;
}
?>
<div class="card mb-4" id="orderChatCard">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Order Chat <small class="text-muted fw-normal">— talk to your rider &amp; staff</small></h6>
    </div>
    <div class="card-body">
        <div id="orderChatBox" style="max-height:300px;overflow-y:auto;" aria-live="polite">
            <div class="text-center text-muted small py-3">Loading messages…</div>
        </div>
        <form id="orderChatForm" class="d-flex gap-2 mt-2" autocomplete="off">
            <input type="text" id="orderChatInput" class="form-control form-control-sm"
                   placeholder="Type a message about this order…" maxlength="1000">
            <button type="submit" class="btn btn-sm btn-dark text-nowrap"><i class="bi bi-send me-1"></i>Send</button>
        </form>
    </div>
</div>
<script>
(function () {
    var ORDER_ID = <?= (int)$chatOrderId ?>;
    var box = document.getElementById('orderChatBox');
    var form = document.getElementById('orderChatForm');
    var input = document.getElementById('orderChatInput');
    if (!box || !form || !input) return;
    var lastId = 0;
    var timer = null;

    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function bubble(m) {
        var mine = !!m.mine;
        var wrap = document.createElement('div');
        wrap.style.cssText = 'display:flex;margin-bottom:8px;' + (mine ? 'justify-content:flex-end;' : 'justify-content:flex-start;');
        var role = m.sender_role ? ' <span style="opacity:.65;font-weight:400;">· ' + esc(m.sender_role) + '</span>' : '';
        wrap.innerHTML = '<div style="max-width:80%;padding:8px 12px;border-radius:12px;font-size:.9rem;' +
            (mine ? 'background:#111;color:#fff;border-bottom-right-radius:3px;'
                   : 'background:#f1f1f1;color:#111;border:1px solid #e0e0e0;border-bottom-left-radius:3px;') + '">' +
            '<div style="font-size:.72rem;font-weight:700;margin-bottom:2px;">' + esc(m.sender_name) + role + '</div>' +
            '<div style="white-space:pre-wrap;word-break:break-word;">' + esc(m.message) + '</div>' +
            '<div style="font-size:.68rem;opacity:.6;margin-top:2px;">' + esc(m.created_at || '') + '</div></div>';
        return wrap;
    }

    function load() {
        fetch('/api/chat?order_id=' + ORDER_ID + '&after=' + lastId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (d) {
            if (!d || !d.success) return;
            var first = (lastId === 0);
            if (first) box.innerHTML = '';
            (d.messages || []).forEach(function (m) {
                box.appendChild(bubble(m));
                if (m.id > lastId) lastId = m.id;
            });
            if (first && lastId === 0) {
                box.innerHTML = '<div class="text-center text-muted small py-3">No messages yet — say hello!</div>';
            } else {
                box.scrollTop = box.scrollHeight;
            }
        })
        .catch(function () { /* retry next cycle */ });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var text = input.value.trim();
        if (!text) return;
        var fd = new FormData();
        fd.append('order_id', ORDER_ID);
        fd.append('message', text);
        input.value = '';
        fetch('/api/chat', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d && d.success && d.message) {
                    if (lastId === 0) box.innerHTML = '';
                    box.appendChild(bubble(d.message));
                    lastId = Math.max(lastId, d.message.id);
                    box.scrollTop = box.scrollHeight;
                } else {
                    alert((d && d.message) || 'Could not send message.');
                    input.value = text;
                }
            })
            .catch(function () {
                alert('Network error. Please try again.');
                input.value = text;
            });
    });

    load();
    timer = setInterval(load, 5000);
    document.addEventListener('visibilitychange', function () {
        if (document.hidden && timer) { clearInterval(timer); timer = null; }
        else if (!document.hidden && !timer) { load(); timer = setInterval(load, 5000); }
    });
})();
</script>
