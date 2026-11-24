<style>
    .wa-chat-modal .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.28);
        background: #efeae2;
    }

    .wa-chat-header {
        background: linear-gradient(135deg, #075e54 0%, #128c7e 100%);
        color: #ffffff;
        padding: 14px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    .wa-chat-header .text-muted {
        color: rgba(255, 255, 255, 0.82) !important;
        font-size: 11.5px;
    }

    .wa-chat-header .btn-close {
        filter: invert(1) brightness(2);
        opacity: 0.85;
    }

    .wa-chat-header .btn-close:hover {
        opacity: 1;
    }

    .wa-avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .wa-chat-body {
        padding: 14px;
        background-color: #efeae2;
        background-image: radial-gradient(rgba(17, 27, 33, 0.05) 1px, transparent 0);
        background-size: 18px 18px;
    }

    #chatMessagesContainer.wa-messages-box {
        height: 290px;
        overflow-y: auto;
        border: none !important;
        border-radius: 14px !important;
        padding: 12px !important;
        margin-bottom: 12px !important;
        background: rgba(255, 255, 255, 0.45);
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .wa-msg-row {
        display: flex;
        flex-direction: column;
        max-width: 80%;
    }

    .wa-msg-row.is-mine {
        align-self: flex-end;
        align-items: flex-end;
    }

    .wa-msg-row.is-other {
        align-self: flex-start;
        align-items: flex-start;
    }

    .wa-msg-bubble {
        padding: 8px 12px;
        border-radius: 12px;
        font-size: 13.5px;
        line-height: 1.42;
        color: #111b21;
        box-shadow: 0 1px 2px rgba(11, 20, 26, 0.13);
        word-break: break-word;
    }

    .wa-msg-row.is-mine .wa-msg-bubble {
        background: #d9fdd3;
        border-top-right-radius: 2px;
    }

    .wa-msg-row.is-other .wa-msg-bubble {
        background: #ffffff;
        border-top-left-radius: 2px;
    }

    .wa-msg-sender {
        font-size: 11px;
        font-weight: 700;
        color: #075e54;
        margin-bottom: 2px;
    }

    .wa-msg-time {
        font-size: 10.5px;
        color: #667781;
        margin-top: 2px;
        padding: 0 2px;
    }

    .wa-input-bar {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .wa-input-field {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 999px !important;
        padding: 10px 16px !important;
        font-size: 13.5px;
        color: #111b21 !important;
        box-shadow: 0 1px 3px rgba(11, 20, 26, 0.08) !important;
    }

    .wa-input-field:focus {
        border-color: #128c7e !important;
        box-shadow: 0 0 0 2px rgba(18, 140, 126, 0.18) !important;
    }

    .wa-send-btn {
        width: 42px;
        height: 42px;
        border-radius: 50% !important;
        background: #008069 !important;
        border: none !important;
        color: #ffffff !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 128, 105, 0.35);
        transition: transform 0.15s ease, background-color 0.15s ease;
    }

    .wa-send-btn:hover {
        background: #017561 !important;
        transform: scale(1.04);
    }
</style>

<div class="modal fade wa-chat-modal" id="taskChatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header wa-chat-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2.5 min-w-0">
                    <span class="wa-avatar-circle">
                        <i class="bi bi-chat-left-text-fill"></i>
                    </span>
                    <div class="min-w-0">
                        <h6 class="fw-bold modal-title text-white m-0 text-truncate" id="chatTaskTitle">Task Chat</h6>
                        <small class="text-muted d-block text-truncate" id="chatTaskStatus">Status</small>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body wa-chat-body">
                <div id="chatMessagesContainer" class="wa-messages-box">
                    <p class="text-center text-muted small my-auto">Loading comments...</p>
                </div>
                <form id="sendCommentForm" class="wa-input-bar">
                    <input type="text" id="commentInput" class="form-control wa-input-field" placeholder="Type a message..." required autocomplete="off">
                    <button type="submit" class="btn wa-send-btn" aria-label="Send message">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function formatWaCommentTime(isoString) {
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

    function renderWaChatMessages(comments = [], currentUser = '') {
        const container = document.getElementById('chatMessagesContainer');
        if (!container) return;

        if (!Array.isArray(comments) || comments.length === 0) {
            container.innerHTML = '<p class="text-center text-muted small my-auto">No messages yet. Start the discussion.</p>';
            return;
        }

        const me = String(currentUser || localStorage.getItem('user_name') || '').trim().toLowerCase();

        container.innerHTML = comments.map(c => {
            const sender = c.user || c.sender_name || c.user_name || 'User';
            const isMine = me && String(sender).trim().toLowerCase() === me;
            const text = c.comment || c.message || '';
            const timeText = formatWaCommentTime(c.created_at);

            return `
                <div class="wa-msg-row ${isMine ? 'is-mine' : 'is-other'}">
                    <div class="wa-msg-bubble">
                        ${!isMine ? `<div class="wa-msg-sender">${escapeHtml(sender)}</div>` : ''}
                        <div>${escapeHtml(text)}</div>
                    </div>
                    ${timeText ? `<span class="wa-msg-time">${escapeHtml(timeText)}</span>` : ''}
                </div>
            `;
        }).join('');

        container.scrollTop = container.scrollHeight;
    }

    document.getElementById('sendCommentForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('commentInput');
        const submitBtn = e.currentTarget.querySelector('button[type="submit"]');
        const commentText = input.value.trim();
        if (!activeTaskIdForChat || !commentText) return;

        try {
            input.disabled = true;
            if (submitBtn) submitBtn.disabled = true;

            let res = await apiFetch(`/api/tasks/${activeTaskIdForChat}/comments`, {
                method: 'POST',
                body: JSON.stringify({ comment: commentText })
            });

            if (!res || !res.ok) {
                res = await apiFetch(`/api/admin/tasks/${activeTaskIdForChat}/comments`, {
                    method: 'POST',
                    body: JSON.stringify({ comment: commentText })
                });
            }

            if (res && res.ok) {
                input.value = '';
                await loadComments(activeTaskIdForChat);
            }
        } finally {
            input.disabled = false;
            if (submitBtn) submitBtn.disabled = false;
            input.focus();
        }
    });
</script>