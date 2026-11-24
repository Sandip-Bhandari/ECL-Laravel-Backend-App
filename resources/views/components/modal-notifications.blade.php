@extends('layouts.app')

@section('content')
<style>
    .tms-common-notif-wrap {
        max-width: 820px;
        margin: 0 auto;
    }

    .tms-common-notif-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 13px 16px;
        background: #ffffff;
        cursor: pointer;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }

    .tms-common-notif-card + .tms-common-notif-card {
        margin-top: 10px;
    }

    .tms-common-notif-card.is-unread {
        background: #fffbeb;
        border-color: #fcd34d;
        border-left: 4px solid #d97706;
    }

    .tms-common-notif-card.is-read {
        background: #ffffff;
        opacity: 0.85;
    }
</style>

<div class="container py-3 tms-common-notif-wrap">
    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-bold text-dark mb-0">Notifications</h5>
                <span class="badge rounded-pill bg-warning text-dark" id="commonNotifUnreadBadge">0 Unread</span>
            </div>
            <button type="button" class="btn btn-light btn-sm border rounded-pill px-3 py-1 fw-semibold" onclick="markAllCommonNotifsRead()">
                Mark all read
            </button>
        </div>

        <div id="commonNotificationsList">
            <p class="text-center text-muted my-3">Loading notifications...</p>
        </div>
    </div>
</div>

<script>
    let commonNotifsStore = [];

    function escapeCommonNotifHtml(str) {
        if (typeof escapeHtml === 'function') return escapeHtml(String(str ?? ''));
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatCommonNotifTime(val) {
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

    function isCommonNotifUnread(item) {
        if (!item) return false;
        if (typeof item.is_read === 'boolean') return !item.is_read;
        if (typeof item.read === 'boolean') return !item.read;
        if (item.read_at !== undefined) return !item.read_at;
        return false;
    }

    function updateCommonNotifBadge() {
        const badge = document.getElementById('commonNotifUnreadBadge');
        if (!badge) return;
        const count = commonNotifsStore.filter(isCommonNotifUnread).length;
        badge.textContent = `${count} Unread`;
        badge.className = count > 0
            ? 'badge rounded-pill bg-warning text-dark'
            : 'badge rounded-pill bg-light text-muted border';
    }

    function renderCommonNotifications(items = []) {
        commonNotifsStore = Array.isArray(items) ? items : [];
        const container = document.getElementById('commonNotificationsList');
        if (!container) return;

        updateCommonNotifBadge();

        if (!commonNotifsStore.length) {
            container.innerHTML = '<p class="text-center text-muted my-3">No notifications.</p>';
            return;
        }

        container.innerHTML = commonNotifsStore.map((item, idx) => {
            const unread = isCommonNotifUnread(item);
            const title = item.title || item.data?.title || 'Notification';
            const message = item.message || item.data?.message || '';
            const timeText = formatCommonNotifTime(item.created_at);
            const badgeHtml = unread
                ? '<span class="badge rounded-pill bg-warning text-dark">Unread</span>'
                : '<span class="badge rounded-pill bg-light text-muted border">Read</span>';

            return `
                <div class="tms-common-notif-card ${unread ? 'is-unread' : 'is-read'}" onclick="markSingleCommonNotifRead(${idx})">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <strong class="text-dark small">${escapeCommonNotifHtml(title)}</strong>
                        <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                            ${badgeHtml}
                            <small class="text-muted">${escapeCommonNotifHtml(timeText)}</small>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">${escapeCommonNotifHtml(message)}</p>
                </div>
            `;
        }).join('');
    }

    async function markSingleCommonNotifRead(index) {
        const item = commonNotifsStore[index];
        if (!item || !isCommonNotifUnread(item)) return;

        const nowIso = new Date().toISOString();
        item.is_read = true;
        item.read = true;
        item.read_at = nowIso;
        renderCommonNotifications(commonNotifsStore);

        if (item.id && typeof apiFetch === 'function') {
            try {
                await apiFetch(`/api/notifications/${item.id}/read`, { method: 'POST' });
            } catch (_) {}
        }
    }

    async function markAllCommonNotifsRead() {
        const unread = commonNotifsStore.filter(n => isCommonNotifUnread(n) && n.id);
        if (!unread.length) return;

        const nowIso = new Date().toISOString();
        commonNotifsStore = commonNotifsStore.map(n => ({
            ...n,
            is_read: true,
            read: true,
            read_at: n.read_at || nowIso
        }));
        renderCommonNotifications(commonNotifsStore);

        if (typeof apiFetch === 'function') {
            await Promise.allSettled(
                unread.map(n => apiFetch(`/api/notifications/${n.id}/read`, { method: 'POST' }))
            );
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        if (typeof apiFetch === 'function') {
            try {
                const res = await apiFetch('/api/notifications');
                if (res && res.ok) {
                    const payload = await res.json();
                    const list = Array.isArray(payload) ? payload : (payload.data || payload.notifications || []);
                    renderCommonNotifications(list);
                }
            } catch (_) {}
        }
    });
</script>
@endsection