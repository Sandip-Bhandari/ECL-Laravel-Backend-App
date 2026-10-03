<div class="modal fade" id="taskChatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 p-2">
            <div class="modal-header border-0 pb-2">
                <div>
                    <h6 class="fw-bold modal-title m-0" id="chatTaskTitle">Task Chat</h6>
                    <small class="text-muted" id="chatTaskStatus">Status</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="chatMessagesContainer" style="height: 250px; overflow-y: auto;" class="mb-3 p-2 border rounded">
                    <p class="text-center text-muted">Loading comments...</p>
                </div>
                <form id="sendCommentForm" class="d-flex gap-2">
                    <input type="text" id="commentInput" class="form-control app-input" placeholder="Type a comment..." required>
                    <button type="submit" class="btn btn-app-dark"><i class="bi bi-send-fill"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('sendCommentForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('commentInput');
        if (!activeTaskIdForChat || !input.value.trim()) return;

        let res = await apiFetch(`/api/tasks/${activeTaskIdForChat}/comments`, {
            method: 'POST',
            body: JSON.stringify({ comment: input.value })
        });

        if (!res || !res.ok) {
            res = await apiFetch(`/api/admin/tasks/${activeTaskIdForChat}/comments`, {
                method: 'POST',
                body: JSON.stringify({ comment: input.value })
            });
        }

        input.value = '';
        loadComments(activeTaskIdForChat);
    });
</script>