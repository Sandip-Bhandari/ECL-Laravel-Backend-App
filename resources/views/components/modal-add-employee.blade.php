<div class="modal fade" id="addEmpModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 p-2">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold modal-title" style="color: var(--app-dark);">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addEmpForm">
                    <div class="mb-2">
                        <input type="text" id="empName" class="form-control app-input" placeholder="Full Name" required>
                    </div>
                    <div class="mb-2">
                        <input type="email" id="empEmail" class="form-control app-input" placeholder="Email Address" required>
                    </div>
                    <div class="mb-2">
                        <input type="password" id="empPassword" class="form-control app-input" placeholder="Password (optional)" minlength="6">
                    </div>
                    <div class="mb-3">
                        <select id="empDeptSelect" class="form-select app-input">
                            <option value="">Select Department...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <select id="empRoleSelect" class="form-select app-input" required>
                            <option value="employee">Employee</option>
                            <option value="admin">Admin</option>
                            <option value="admin,employee">Admin and employee</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-app-dark w-100">Save Employee</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('addEmpForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.currentTarget;
        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;
        try {
            const res = await apiFetch('/api/employees', {
                method: 'POST',
                body: JSON.stringify({
                name: document.getElementById('empName').value,
                email: document.getElementById('empEmail').value,
                password: document.getElementById('empPassword').value,
                department_id: document.getElementById('empDeptSelect').value || null,
                role: document.getElementById('empRoleSelect').value
            })
            });
            const result = await res.json();
            if (!res.ok) throw new Error(result.message || 'Failed to create employee.');
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('addEmpModal'))?.hide();
            fetchEmployeesAndDepts();
        } catch (error) {
            window.alert(error.message);
        } finally {
            button.disabled = false;
        }
    });
</script>