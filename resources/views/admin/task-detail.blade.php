<style>
    .tms-wa-modal .modal-content {
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.28);
        background: #ffffff;
    }

    .tms-wa-modal .modal-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 20px;
    }

    .tms-wa-meta-strip {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 12.5px;
    }

    .tms-wa-desc-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #075e54;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 13.5px;
        color: #111b21;
    }

    .tms-wa-status-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .wa-chat-zone {
        background-color: #efeae2;
        background-image: radial-gradient(rgba(17, 27, 33, 0.055) 1px, transparent 0);
        background-size: 18px 18px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 12px;
    }

    .wa-chat-top {
        background: linear-gradient(135deg, #075e54 0%, #128c7e 100%);
        color: #ffffff;
        border-radius: 11px;
        padding: 8px 12px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
    }

    .wa-msg-thread {
        height: 250px;
        overflow-y: auto;
        padding: 6px;
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .wa-row {
        display: flex;
        flex-direction: column;
        max-width: 78%;
    }

    .wa-row.is-mine {
        align-self: flex-end;
        align-items: flex-end;
    }

    .wa-row.is-other {
        align-self: flex-start;
        align-items: flex-start;
    }

    .wa-bubble {
        padding: 8px 12px;
        border-radius: 12px;
        font-size: 13.5px;
        line-height: 1.42;
        color: #111b21;
        box-shadow: 0 1px 2px rgba(11, 20, 26, 0.14);
        word-break: break-word;
    }

    .wa-row.is-mine .wa-bubble {
        background: #d9fdd3;
        border-top-right-radius: 2px;
    }

    .wa-row.is-other .wa-bubble {
        background: #ffffff;
        border-top-left-radius: 2px;
    }

    .wa-sender {
        font-size: 11px;
        font-weight: 700;
        color: #075e54;
        margin-bottom: 2px;
    }

    .wa-clock {
        font-size: 10.5px;
        color: #667781;
        margin-top: 2px;
        padding: 0 2px;
    }

    .wa-send-bar {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .wa-text-pill {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 999px !important;
        padding: 9px 15px !important;
        font-size: 13.5px;
        color: #111b21 !important;
    }

    .wa-send-btn {
        width: 40px;
        height: 40px;
        border-radius: 50% !important;
        background: #008069 !important;
        border: none !important;
        color: #ffffff !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>

<div class="modal fade tms-wa-modal" id="sharedTaskDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-start gap-3">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0.5" id="sharedModalTitle">Task Title</h5>
                    <small class="text-muted" id="sharedModalDept">Department</small>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-3 p-md-4 vstack gap-3">
                <div class="tms-wa-meta-strip">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="badge rounded-pill bg-success text-white px-3 py-1.5" id="sharedModalStatusBadge">Completed</span>
                        <span class="badge rounded-pill bg-light text-dark border px-3 py-1.5" id="sharedModalPriorityBadge">High priority</span>
                        <span class="text-muted">Assigned to: <strong class="text-dark" id="sharedModalAssignee">--</strong></span>
                    </div>
                    <span class="text-muted fw-medium" id="sharedModalDue">Due: --</span>
                </div>

                <div class="tms-wa-desc-box" id="sharedModalDesc">No description provided.</div>

                <div class="tms-wa-status-bar">
                    <label for="sharedModalStatusSelect" class="fw-semibold text-dark small mb-0">Update status</label>
                    <select id="sharedModalStatusSelect" class="form-select form-select-sm bg-white rounded-3" style="max-width: 240px;">
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Approval for Completed">Approval for Completed</option>
                        <option value="Completed">Completed</option>
                    </select>
                    <button type="button" class="btn btn-dark btn-sm rounded-3 px-3 fw-semibold" id="sharedModalSaveBtn">
                        Save
                    </button>
                </div>

                <div class="wa-chat-zone">
                    <div class="wa-chat-top">
                        <span><i class="bi bi-chat-dots-fill me-1.5"></i>Task discussion</span>
                        <span class="small opacity-75">WhatsApp Theme</span>
                    </div>

                    <div class="wa-msg-thread" id="sharedChatMessages"></div>

                    <form class="wa-send-bar" id="sharedChatForm">
                        <input type="text" id="sharedChatInput" class="form-control wa-text-pill" placeholder="Write a message..." required autocomplete="off">
                        <button type="submit" class="btn wa-send-btn" aria-label="Send message">
                            <i class="bi bi-send-fill small"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function escapeWaText(str) {
        if (typeof escapeHtml === 'function') return escapeHtml(String(str ?? ''));
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatWaStamp(isoString) {
        if (!isoString) return '';
        const dt = new Date(isoString);
        if (Number.isNaN(dt.getTime())) return String(isoString);
        return dt.toLocaleString('en-IN', {
            day: '2-digit',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function renderWaChatBubbles(comments = [], currentUserId = null, currentUserName = '') {
        const box = document.getElementById('sharedChatMessages');
        if (!box) return;

        if (!Array.isArray(comments) || comments.length === 0) {
            box.innerHTML = '<p class="text-center text-muted small my-auto">No discussion messages yet.</p>';
            return;
        }

        const myId = currentUserId ?? localStorage.getItem('user_id');
        const myName = String(currentUserName || localStorage.getItem('user_name') || '').trim().toLowerCase();

        box.innerHTML = comments.map(c => {
            const sender = c.user?.name || c.user || c.sender_name || c.user_name || 'User';
            const isMine = (myId !== null && myId !== '' && c.user_id !== undefined && c.user_id !== null)
                ? Number(c.user_id) === Number(myId)
                : Boolean(myName && String(sender).trim().toLowerCase() === myName);

            const body = c.message || c.comment || '';
            const timeText = formatWaStamp(c.created_at);

            return `
                <div class="wa-row ${isMine ? 'is-mine' : 'is-other'}">
                    <div class="wa-bubble">
                        ${!isMine ? `<div class="wa-sender">${escapeWaText(sender)}</div>` : ''}
                        <div>${escapeWaText(body)}</div>
                    </div>
                    ${timeText ? `<span class="wa-clock">${escapeWaText(timeText)}</span>` : ''}
                </div>
            `;
        }).join('');

        box.scrollTop = box.scrollHeight;
    }
</script>