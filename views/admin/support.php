<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-headset me-2"></i>Support Chats</h1>
        <p class="text-muted small mb-0">Customer support threads — newest activity first.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Threads</h5></div>
            <div class="list-group list-group-flush" id="threadList" style="max-height:60vh;overflow-y:auto;">
                <?php if (empty($threads)): ?>
                    <div class="p-3 text-muted small text-center">No support messages yet.</div>
                <?php else: ?>
                    <?php foreach ($threads as $t): ?>
                        <button type="button" class="list-group-item list-group-item-action thread-btn"
                                data-customer="<?= (int)$t['customer_id'] ?>"
                                data-name="<?= htmlspecialchars($t['customer_name']) ?>">
                            <div class="d-flex justify-content-between">
                                <strong class="small"><?= htmlspecialchars($t['customer_name']) ?></strong>
                                <small class="text-muted"><?= date('M j H:i', strtotime($t['last_at'])) ?></small>
                            </div>
                            <small class="text-muted"><?= htmlspecialchars($t['customer_email']) ?> · <?= (int)$t['total'] ?> msgs</small>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0" id="threadTitle">Select a thread</h5></div>
            <div class="card-body">
                <div id="adminChatBox" style="max-height:50vh;overflow-y:auto;">
                    <div class="text-center text-muted small py-4">Choose a customer thread to read and reply.</div>
                </div>
                <form id="adminChatForm" class="d-flex gap-2 mt-2" style="display:none;" autocomplete="off">
                    <input type="text" id="adminChatInput" class="form-control"
                           placeholder="Reply as staff…" maxlength="1000">
                    <button type="submit" class="btn btn-dark"><i class="bi bi-send me-1"></i>Send</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var box = document.getElementById('adminChatBox');
    var form = document.getElementById('adminChatForm');
    var input = document.getElementById('adminChatInput');
    var title = document.getElementById('threadTitle');
    var customerId = 0, lastId = 0, timer = null;

    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    function bubble(m) {
        var mine = !!m.mine;
        var wrap = document.createElement('div');
        wrap.style.cssText = 'display:flex;margin-bottom:8px;' + (mine ? 'justify-content:flex-end;' : 'justify-content:flex-start;');
        wrap.innerHTML = '<div style="max-width:80%;padding:8px 12px;border-radius:12px;font-size:.9rem;' +
            (mine ? 'background:#111;color:#fff;border-bottom-right-radius:3px;'
                   : 'background:#f1f1f1;color:#111;border:1px solid #e0e0e0;border-bottom-left-radius:3px;') + '">' +
            '<div style="font-size:.72rem;font-weight:700;margin-bottom:2px;">' + esc(m.sender_name) + '</div>' +
            '<div style="white-space:pre-wrap;word-break:break-word;">' + esc(m.message) + '</div>' +
            '<div style="font-size:.68rem;opacity:.6;margin-top:2px;">' + esc(m.created_at || '') + '</div></div>';
        return wrap;
    }
    function load() {
        if (!customerId) return;
        fetch('/api/support-chat?customer_id=' + customerId + '&after=' + lastId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (d) {
            if (!d || !d.success) return;
            if (lastId === 0) box.innerHTML = '';
            (d.messages || []).forEach(function (m) {
                box.appendChild(bubble(m));
                if (m.id > lastId) lastId = m.id;
            });
            if (lastId === 0) box.innerHTML = '<div class="text-center text-muted small py-4">No messages in this thread yet.</div>';
            else box.scrollTop = box.scrollHeight;
        })
        .catch(function () {});
    }
    document.querySelectorAll('.thread-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.thread-btn').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            customerId = parseInt(btn.getAttribute('data-customer'), 10);
            lastId = 0;
            title.textContent = 'Chat with ' + btn.getAttribute('data-name');
            box.innerHTML = '<div class="text-center text-muted small py-4">Loading…</div>';
            form.style.display = 'flex';
            load();
            if (timer) clearInterval(timer);
            timer = setInterval(load, 5000);
        });
    });
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var text = input.value.trim();
        if (!text || !customerId) return;
        var params = new URLSearchParams();
        params.append('message', text);
        params.append('customer_id', customerId);
        input.value = '';
        fetch('/api/chat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: params.toString()
        })
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
})();
</script>

<?php
$content = ob_get_clean();
$title = 'Support Chats - CafeCat';
$currentPage = 'support';
include __DIR__ . '/layout.php';
?>
