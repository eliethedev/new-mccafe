/* CafeCat delivery notification bell — real-time polling widget.
 * Usage (per layout):
 *   CafeCatNotifications.initBell({
 *     badgeId: 'notifBadge', listId: 'notifList', markAllId: 'notifMarkAll',
 *     bellId: 'notifBell', ordersUrl: '/orders'
 *   });
 * Polls GET /api/notifications every 30s, updates the badge, renders the
 * dropdown, and pops a toast for new ready/delivered notices.
 */
(function () {
    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function showToast(title, message, link, ntype) {
        var wrap = document.getElementById('cc-notif-toasts');
        if (!wrap) {
            wrap = document.createElement('div');
            wrap.id = 'cc-notif-toasts';
            wrap.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;max-width:340px;';
            document.body.appendChild(wrap);
        }
        var el = document.createElement('div');
        el.style.cssText = 'background:#111;color:#fff;border-radius:10px;padding:12px 14px;box-shadow:0 8px 24px rgba(0,0,0,.35);cursor:pointer;';
        el.innerHTML = '<div style="font-weight:700;margin-bottom:4px;">🔔 ' + esc(title) + '</div>' +
            '<div style="font-size:.85rem;opacity:.85;">' + esc(message) + '</div>';
        if (link) {
            el.addEventListener('click', function () { window.location.href = link; });
        } else if (ntype === 'chat_message' && window.CafeCatSupport) {
            el.addEventListener('click', function () { window.CafeCatSupport.open(); });
        }
        wrap.appendChild(el);
        setTimeout(function () { if (el.parentNode) el.parentNode.removeChild(el); }, 8000);
    }

    function isDelivery(n) {
        return n && (n.type === 'order_delivered' || n.type === 'order_ready' ||
                     n.type === 'refund_processed' || n.type === 'refund_updated' ||
                     n.type === 'delivery_assigned' || n.type === 'delivery_updated' ||
                     n.type === 'substitution_suggested' || n.type === 'substitution_decided' ||
                     n.type === 'chat_message');
    }

    function initBell(opts) {
        var badge = document.getElementById(opts.badgeId);
        var list = document.getElementById(opts.listId);
        var markAll = opts.markAllId ? document.getElementById(opts.markAllId) : null;
        var bell = opts.bellId ? document.getElementById(opts.bellId) : null;
        if (!badge || !list) return;

        var lastSeenMaxId = 0;
        var firstPoll = true;

        function render(items, unread) {
            badge.textContent = unread > 99 ? '99+' : String(unread);
            badge.style.display = unread > 0 ? 'flex' : 'none';
            if (!items.length) {
                list.innerHTML = '<li class="px-3 py-3 text-muted small text-center">No notifications yet.</li>' + footerHtml();
                return;
            }
            list.innerHTML = items.map(function (n) {
                var icon = n.type === 'order_delivered' ? 'bi-bag-check-fill'
                    : (n.type === 'order_ready' ? 'bi-bell-fill'
                    : ((n.type === 'refund_processed' || n.type === 'refund_updated') ? 'bi-arrow-clockwise'
                    : ((n.type === 'delivery_assigned' || n.type === 'delivery_updated') ? 'bi-bicycle'
                    : ((n.type === 'substitution_suggested' || n.type === 'substitution_decided') ? 'bi-arrow-left-right'
                    : (n.type === 'chat_message' ? 'bi-chat-dots'
                    : 'bi-info-circle-fill')))));
                var href = n.link ? ' href="' + esc(n.link) + '"' : (n.type === 'chat_message' ? ' href="#" data-open-support="1" onclick="return false;"' : ' href="#" onclick="return false;"');
                return '<li><a' + href + ' class="dropdown-item d-flex gap-2 py-2" data-notif-id="' + n.id + '" data-notif-type="' + esc(n.type || '') + '">' +
                    '<i class="bi ' + icon + ' mt-1"></i>' +
                    '<span><strong class="d-block small">' + esc(n.title) + '</strong>' +
                    '<span class="small text-muted">' + esc(n.message || '') + '</span>' +
                    (!parseInt(n.is_read, 10) ? ' <span class="badge bg-dark ms-1">new</span>' : '') +
                    '</span></a></li>';
            }).join('') + footerHtml();
        }

        function footerHtml() {
            return '<li><hr class="dropdown-divider"></li>' +
                '<li class="d-flex justify-content-between px-3 pb-1">' +
                '<button type="button" class="btn btn-sm btn-link p-0" data-notif-mark-all>Mark all read</button>' +
                '<a class="btn btn-sm btn-link p-0" href="' + esc(opts.ordersUrl || '/orders') + '">View orders</a></li>';
        }

        function poll() {
            fetch('/api/notifications', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.ok ? r.json() : null; })
                .then(function (d) {
                    if (!d || !d.success) return;
                    render(d.notifications || [], d.unread_count || 0);
                    var ids = (d.notifications || []).map(function (n) { return n.id; });
                    var maxId = ids.length ? Math.max.apply(null, ids) : 0;
                    var fresh = (d.notifications || []).filter(function (n) {
                        return isDelivery(n) && !parseInt(n.is_read, 10) &&
                            (firstPoll || n.id > lastSeenMaxId);
                    });
                    fresh.forEach(function (n) { showToast(n.title, n.message, n.link, n.type); });
                    firstPoll = false;
                    if (maxId > lastSeenMaxId) lastSeenMaxId = maxId;
                })
                .catch(function () { /* offline — try again next cycle */ });
        }

        function markAllRead() {
            var formData = new FormData();
            fetch('/api/notifications/read', { method: 'POST', body: formData })
                .then(function (r) { return r.json(); })
                .then(function () { poll(); })
                .catch(function () {});
        }

        if (markAll) {
            markAll.addEventListener('click', function (e) { e.preventDefault(); markAllRead(); });
        }
        // Delegated handler for the in-list "Mark all read" button.
        list.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-notif-mark-all]');
            if (btn) { e.preventDefault(); markAllRead(); return; }
            var item = e.target.closest('[data-notif-id]');
            if (item) {
                var opener = e.target.closest('[data-open-support]');
                if (opener && window.CafeCatSupport) { window.CafeCatSupport.open(); }
                var fd = new FormData();
                fd.append('ids[]', item.getAttribute('data-notif-id'));
                fetch('/api/notifications/read', { method: 'POST', body: fd }).catch(function () {});
            }
        });
        if (bell) {
            bell.addEventListener('click', function () {
                // Refresh on open so the badge is current.
                setTimeout(poll, 500);
            });
        }

        poll();
        setInterval(poll, 30000);
    }

    window.CafeCatNotifications = { initBell: initBell };
})();
