<style>
    .tms-notif-modal .modal-content {
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        box-shadow: 0 22px 54px rgba(15, 23, 42, 0.24);
        overflow: hidden;
        background: #ffffff;
    }

    .tms-notif-modal .modal-header {
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e2e8f0;
        padding: 15px 20px;
    }

    .tms-notif-icon-badge {
        width: 36px;
        height: 36px;
        border-radius: 11px;
        background: #fef3c7;
        color: #b45309;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .tms-notif-modal .modal-body {
        max-height: 400px;
        overflow-y: auto;
        padding: 14px 18px;
        background: #f8fafc;
    }

    .tms-notif-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #cbd5e1;
        border-radius: 14px;
        padding: 12px 14px;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
        transition: transform 0.16s ease, box-shadow 0.16s ease, border-color 0.16s ease;
        cursor: pointer;
    }

    .tms-notif-item + .tms-notif-item {
        margin-top: 10px;
    }

    .tms-notif-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.07);
    }

    /* Unread notification styling */
    .tms-notif-item.is-unread {
        background: #fffbeb;
        border-color: #fde68a;
        border-left: 4px solid #d97706;
    }

    /* Read notification styling */
    .tms-notif-item.is-read {
        background: #ffffff;
        opacity: 0.85;
    }

    .tms-unread-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #d97706;
        display: inline-block;
        flex-shrink: 0;
    }

    .tms-notif-time {
        font-size: 11px;
        color: #64748b;
    }
</style>

<div class="modal fade tms-notif-modal" id="notifModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2.5">
                    <span class="tms-notif-icon-badge">
                        <i class="bi bi-bell-fill"></i>
                    </span>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="fw-bold modal-title text-dark mb-0">Notifications</h6>
                            <span class="badge rounded-pill bg-warning text-dark" id="notifUnreadCountBadge" style="font-size: 10.5px;">0 Unread</span>
                        </div>
                        <small class="text-muted" style="font-size: 11.5px;">Tap an unread item to mark as read</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 11.5px;" onclick="markAllNotificationsRead()">
                        Mark all read
                    </button>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body" id="notifListContainer">
                <div class="text-center text-muted py-4">
                    <div class="spinner-border spinner-border-sm text-secondary mb-2" role="status"></div>
                    <p class="small mb-0">Loading notifications...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let latestNotificationsCache = [];

    function formatNotifTime(val) {
        if (!val) return '';
        const dt = new Date(val);
        if (Number.isNaN(dt.getTime())) return String(val);
        return dt.toLocaleString('en-IN', {
            day: '2-digit',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function isNotificationUnread(item) {
        if (typeof item.is_read === 'boolean') return !item.is_read;
        if (typeof item.read === 'boolean') return !item.read;
        if (item.read_at !== undefined) return !item.read_at;
        return false;
    }

    function updateUnreadBadgeCount(items = []) {
        const badge = document.getElementById('notifUnreadCountBadge');
        if (!badge) return;
        const unreadCount = items.filter(isNotificationUnread).length;
        badge.textContent = `${unreadCount} Unread`;
        badge.className = unreadCount > 0
            ? 'badge rounded-pill bg-warning text-dark'
            : 'badge rounded-pill bg-secondary-subtle text-muted border';
    }

    function renderNotificationsList(items = []) {
        latestNotificationsCache = Array.isArray(items) ? items : [];
        const container = document.getElementById('notifListContainer');
        if (!container) return;

        updateUnreadBadgeCount(latestNotificationsCache);

        if (latestNotificationsCache.length === 0) {
            container.innerHTML = '<p class="text-center text-muted small my-3 mb-0">No notifications available.</p>';
            return;
        }

        container.innerHTML = latestNotificationsCache.map((item, idx) => {
            const unread = isNotificationUnread(item);
            const stateClass = unread ? 'is-unread' : 'is-read';
            const title = item.title || item.data?.title || 'Notification';
            const message = item.message || item.data?.message || '';
            const timeText = formatNotifTime(item.created_at);
            const stateBadge = unread
                ? '<span class="badge rounded-pill bg-warning text-dark px-2 py-0.5" style="font-size: 10px;">Unread</span>'
                : '<span class="badge rounded-pill bg-light text-muted border px-2 py-0.5" style="font-size: 10px;">Read</span>';

            return `
                <div class="tms-notif-item ${stateClass}" data-notif-id="${escapeHtml(String(item.id ?? idx))}" onclick="handleNotificationClick(${idx})">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <div class="d-flex align-items-center gap-1.5 min-w-0">
                            ${unread ? '<span class="tms-unread-dot"></span>' : ''}
                            <strong class="text-dark small text-truncate">${escapeHtml(String(title))}</strong>
                        </div>
                        <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                            ${stateBadge}
                            <span class="tms-notif-time">${escapeHtml(timeText)}</span>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">${escapeHtml(String(message))}</p>
                </div>
            `;
        }).join('');
    }

    async function handleNotificationClick(index) {
        const item = latestNotificationsCache[index];
        if (!item) return;

        if (isNotificationUnread(item)) {
            item.is_read = true;
            item.read = true;
            item.read_at = new Date().toISOString();
            renderNotificationsList(latestNotificationsCache);

            if (item.id && typeof apiFetch === 'function') {
                try {
                    await apiFetch(`/api/notifications/${item.id}/read`, { method: 'PATCH' });
                } catch (_) {}
            }
        }
    }

    async function markAllNotificationsRead() {
        if (!latestNotificationsCache.length) return;
        const nowIso = new Date().toISOString();
        latestNotificationsCache = latestNotificationsCache.map(n => ({
            ...n,
            is_read: true,
            read: true,
            read_at: n.read_at || nowIso
        }));
        renderNotificationsList(latestNotificationsCache);

        if (typeof apiFetch === 'function') {
            try {
                await apiFetch('/api/notifications/read-all', { method: 'POST' });
            } catch (_) {}
        }
    }
</script>