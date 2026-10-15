<?php
// Floating customer-support chat widget (customer <-> staff).
// Included by the main layout for logged-in users.
if (!Session::has('user')) {
    return;
}
?>
<style>
#cc-support-fab {
    position: fixed; left: 20px; bottom: 20px; z-index: 9000;
    width: 52px; height: 52px; border-radius: 50%;
    background: #111; color: #fff; border: none;
    font-size: 1.4rem; box-shadow: 0 8px 24px rgba(0,0,0,.35);
}
#cc-support-panel {
    position: fixed; left: 20px; bottom: 84px; z-index: 9000;
    width: 330px; max-width: calc(100vw - 40px);
    display: none;
}
#cc-support-box { max-height: 300px; overflow-y: auto; }
</style>
<button id="cc-support-fab" title="Chat with support" aria-label="Chat with support">
    <i class="bi bi-chat-dots"></i>
</button>
<div id="cc-support-panel" class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong class="small"><i class="bi bi-headset me-1"></i>Customer Support</strong>
        <button type="button" class="btn-close btn-close-sm" id="cc-support-close" aria-label="Close"></button>
    </div>
    <div class="card-body p-2">
        <div id="cc-support-box"><div class="text-center text-muted small py-3">Loading…</div></div>
        <form id="cc-support-form" class="d-flex gap-2 mt-2" autocomplete="off">
            <input type="text" id="cc-support-input" class="form-control form-control-sm"
                   placeholder="Ask us anything…" maxlength="1000">
            <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-send"></i></button>
        </form>
    </div>
</div>
<script>
(function () {
    var fab = document.getElementById('cc-support-fab');
    var panel = document.getElementById('cc-support-panel');
    var box = document.getElementById('cc-support-box');
    var form = document.getElementById('cc-support-form');
    var input = document.getElementById('cc-support-input');
    if (!fab || !panel || !box || !form || !input) return;
    var lastId = 0, timer = null, opened = false;

    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    function bubble(m) {
        var mine = !!m.mine;
        var wrap = document.createElement('div');
        wrap.style.cssText = 'display:flex;margin-bottom:8px;' + (mine ? 'justify-content:flex-end;' : 'justify-content:flex-start;');
        wrap.innerHTML = '<div style="max-width:85%;padding:7px 11px;border-radius:12px;font-size:.85rem;' +
            (mine ? 'background:#111;color:#fff;border-bottom-right-radius:3px;'
                   : 'background:#f1f1f1;color:#111;border:1px solid #e0e0e0;border-bottom-left-radius:3px;') + '">' +
            '<div style="white-space:pre-wrap;word-break:break-word;">' + esc(m.message) + '</div></div>';
        return wrap;
    }
    function load() {
        fetch('/api/support-chat?after=' + lastId, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.ok ? r.json() : null; })
            .then(function (d) {
                if (!d || !d.success) return;
                if (lastId === 0) box.innerHTML = '';
                (d.messages || []).forEach(function (m) {
                    box.appendChild(bubble(m));
                    if (m.id > lastId) lastId = m.id;
                });
                if (lastId === 0) box.innerHTML = '<div class="text-center text-muted small py-3">Hi! How can we help you today?</div>';
                else box.scrollTop = box.scrollHeight;
            })
            .catch(function () {});
    }
    function open() {
        panel.style.display = 'block'; opened = true;
        load();
        if (!timer) timer = setInterval(load, 5000);
        setTimeout(function () { input.focus(); }, 100);
    }
    function close() { panel.style.display = 'none'; opened = false; }
    fab.addEventListener('click', function () { opened ? close() : open(); });
    document.getElementById('cc-support-close').addEventListener('click', close);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var text = input.value.trim();
        if (!text) return;
        var fd = new FormData();
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
            .catch(function () { alert('Network error. Please try again.'); input.value = text; });
    });
    window.CafeCatSupport = { open: open, close: close };
})();
</script>
