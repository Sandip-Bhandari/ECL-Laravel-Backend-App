<div class="modal fade" id="createTaskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 p-2">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold modal-title" style="color: var(--app-dark);">Issue New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createTaskForm">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Task Subject / Title *</label>
                        <input type="text" id="taskTitle" class="form-control app-input" placeholder="e.g. Audit Report" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Assign To Employee *</label>
                        <select id="assignedEmpSelect" class="form-select app-input" required>
                            <option value="">Select Employee...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Instructions / Description *</label>
                        <textarea id="taskDesc" class="form-control app-input" rows="3" placeholder="Enter guidelines..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-app-dark w-100">Submit Task</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('createTaskForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const res = await apiFetch('/api/admin/tasks', {
            method: 'POST',
            body: JSON.stringify({
                title: document.getElementById('taskTitle').value,
                description: document.getElementById('taskDesc').value,
                employee_id: document.getElementById('assignedEmpSelect').value
            })
        });
        if (res && res.ok) {
            document.getElementById('createTaskForm').reset();
            bootstrap.Modal.getInstance(document.getElementById('createTaskModal')).hide();
            fetchTasks();
            fetchDashboard();
        } else {
            alert("Failed to assign task. Please verify employee assignment.");
        }
    });
</script>