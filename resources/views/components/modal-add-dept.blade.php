<div class="modal fade" id="addDeptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 p-2">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold modal-title" style="color: var(--app-dark);">New Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addDeptForm">
                    <div class="mb-3">
                        <input type="text" id="deptName" class="form-control app-input" placeholder="Department Name" required>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-app-dark px-4">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('addDeptForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.currentTarget;
        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;
        try {
            const res = await apiFetch('/api/departments', {
                method: 'POST',
                body: JSON.stringify({ name: document.getElementById('deptName').value })
            });
            const result = await res.json();
            if (!res.ok) throw new Error(result.message || 'Failed to create department.');
            document.getElementById('addDeptForm').reset();
            bootstrap.Modal.getInstance(document.getElementById('addDeptModal'))?.hide();
            fetchEmployeesAndDepts();
        } catch (error) {
            window.alert(error.message);
        } finally {
            button.disabled = false;
        }
    });
</script>