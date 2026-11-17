@extends('layouts.app')

@section('content')

<style>
/* Professional Glassmorphism Dashboard Styling */
.ecl-tms-page { color: #17283a; }
.ecl-tms-page .card { 
    border: 1px solid rgba(255, 255, 255, 0.35) !important; 
    border-radius: 20px !important; 
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2) !important; 
    background: rgba(255, 255, 255, 0.88) !important; 
    backdrop-filter: blur(16px); 
}

/* Section Headings */
.ecl-section-title {
    color: #ffffff !important;
    font-size: 16px;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6);
}

/* Analytics Metric Pill Cards */
.analytics-metric-card {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(220, 226, 232, 0.8);
    border-radius: 14px;
    padding: 14px 18px;
    transition: all 0.2s ease;
}

.analytics-metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    border-color: #e5b72e;
}

/* Quick Actions Cards */
.quick-action-card {
    min-height: 110px;
    transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1) !important;
    border-radius: 16px !important;
    background: rgba(255, 255, 255, 0.92) !important;
    border: 1px solid rgba(255, 255, 255, 0.6) !important;
    position: relative;
    overflow: hidden;
}

.quick-action-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 5px;
}

.qa-card-new::before { background: linear-gradient(180deg, #e5b72e, #b88a14); }
.qa-card-tasks::before { background: linear-gradient(180deg, #1682f5, #0d5bb5); }
.qa-card-team::before { background: linear-gradient(180deg, #169b61, #0e6b42); }

.quick-action-card:hover {
    transform: translateY(-4px) scale(1.01);
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.25) !important;
    background: #ffffff !important;
}

.qa-icon-wrapper {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    transition: transform 0.25s ease;
}

.quick-action-card:hover .qa-icon-wrapper {
    transform: scale(1.1);
}

.filter-chip { 
    border: 1px solid rgba(255, 255, 255, 0.4) !important; 
    background: rgba(255, 255, 255, 0.85) !important; 
    color: #526071 !important; 
    border-radius: 10px !important; 
    padding: 8px 18px; 
    font-weight: 600; 
    cursor: pointer; 
    transition: all 0.2s; 
}
.filter-chip.active { 
    background: #202a33 !important; 
    border-color: #202a33 !important; 
    color: #fff !important; 
}
</style>

<div class="container py-3 ecl-tms-page">

    <!-- 1. OVERVIEW TAB SECTION -->
    <div id="sec-overview" class="tab-content-sec">
        
        <!-- Professional Tasks Analytics Overview Card -->
        <div class="card border-0 shadow-sm mb-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="background: #FDF3D6; color: #B2820A; width: 36px; height: 36px; font-size: 17px;">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h6 class="fw-bold m-0 text-dark" style="font-size: 16px; letter-spacing: -0.2px;">Tasks Analytics & Overview</h6>
                </div>
                <span class="badge bg-warning-subtle text-warning-emphasis fw-bold px-3 py-1.5 rounded-pill" style="font-size: 11px;"><i class="bi bi-circle-fill text-success me-1.5" style="font-size: 8px;"></i> Live Status</span>
            </div>
            
            <div class="row align-items-center g-4">
                <!-- Circular Total Counter Widget -->
                <div class="col-md-auto text-center">
                    <div class="shadow-sm mx-auto p-1" style="width: 130px; height: 130px; border-radius: 50%; background: conic-gradient(#E2B93B 0% 100%, #EFEAE1 100% 100%); display: flex; align-items: center; justify-content: center;">
                        <div style="width: 104px; height: 104px; background: #fff; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                            <span class="fw-bold fs-2 text-dark lh-1" id="statOverviewTotal">0</span>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 9px; letter-spacing: 0.8px; margin-top: 2px;">Total Tasks</small>
                        </div>
                    </div>
                </div>

                <!-- Professional Status Breakdown Grid -->
                <div class="col">
                    <div class="vstack gap-3">
                        <div class="analytics-metric-card d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 13px;">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <span class="fw-semibold text-dark" style="font-size: 14px;">Pending Tasks</span>
                            </div>
                            <span class="fw-bold badge bg-white text-dark border px-3 py-2 shadow-sm fs-6" id="statPendingCount">0</span>
                        </div>

                        <div class="analytics-metric-card d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 13px;">
                                    <i class="bi bi-arrow-repeat"></i>
                                </div>
                                <span class="fw-semibold text-dark" style="font-size: 14px;">In Progress</span>
                            </div>
                            <span class="fw-bold badge bg-white text-dark border px-3 py-2 shadow-sm fs-6" id="statInProgressCount">0</span>
                        </div>

                        <div class="analytics-metric-card d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 13px;">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <span class="fw-semibold text-dark" style="font-size: 14px;">Completed</span>
                            </div>
                            <span class="fw-bold badge bg-white text-dark border px-3 py-2 shadow-sm fs-6" id="statCompletedCount">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h6 class="ecl-section-title mb-3 px-1"><i class="bi bi-lightning-charge-fill text-warning me-1.5"></i> Quick Actions</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card quick-action-card qa-card-new p-3.5 h-100 d-flex flex-row justify-content-between align-items-center" onclick="openCreateTaskModal()" style="cursor: pointer;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="qa-icon-wrapper" style="background-color: #FDF3D6; color: #B2820A;">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">New Task</h6>
                            <small class="text-muted" style="font-size: 11.5px;">Create & assign task</small>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right-circle-fill text-warning fs-5 opacity-75"></i>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card quick-action-card qa-card-tasks p-3.5 h-100 d-flex flex-row justify-content-between align-items-center" onclick="switchTab('tasks')" style="cursor: pointer;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="qa-icon-wrapper" style="background-color: #E6F0FA; color: #1E6091;">
                            <i class="bi bi-list-task"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">All Tasks</h6>
                            <small class="text-muted" style="font-size: 11.5px;">View tasks list</small>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right-circle-fill text-primary fs-5 opacity-75"></i>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card quick-action-card qa-card-team p-3.5 h-100 d-flex flex-row justify-content-between align-items-center" onclick="switchTab('team')" style="cursor: pointer;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="qa-icon-wrapper" style="background-color: #E8F5E9; color: #2E7D32;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">View Team</h6>
                            <small class="text-muted" style="font-size: 11.5px;">Manage members</small>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right-circle-fill text-success fs-5 opacity-75"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- 2. TASKS TAB SECTION -->
    <div id="sec-tasks" class="tab-content-sec d-none">
        <!-- Professional Horizontal Task Header Banner -->
        <div class="card border-0 shadow-sm text-white d-flex flex-row justify-content-between align-items-center mb-4 p-4 rounded-4" style="background: rgba(18, 24, 31, 0.75) !important; backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.12) !important;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="background: rgba(229, 183, 46, 0.2); color: #e5b72e; width: 42px; height: 42px; font-size: 20px;">
                    <i class="bi bi-journal-plus"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1 text-white" style="font-size: 17px; letter-spacing: 0.2px;">Task Management & Operations</h5>
                    <p class="opacity-75 mb-0" style="font-size: 12px; color: rgba(255, 255, 255, 0.7);">Allocate new operational responsibilities & set strict deadlines</p>
                </div>
            </div>
            <button class="btn fw-bold px-4 py-2.5 rounded-pill shadow-sm flex-shrink-0 d-flex align-items-center gap-2" onclick="openCreateTaskModal()" style="background: linear-gradient(135deg, #e5b72e 0%, #b88a14 100%); color: #12181f; font-size: 13.5px;">
                <i class="bi bi-plus-lg"></i> New Task
            </button>
        </div>

        <div class="position-relative mb-3">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" id="searchInput" class="form-control ps-5 py-3 shadow-none rounded-4" placeholder="Search tasks by title or description..." style="font-size: 13.5px;">
        </div>

        <div class="d-flex gap-2 overflow-auto pb-2 mb-4">
            <span class="filter-chip active shadow-sm" onclick="filterTasks('all', this)">All</span>
            <span class="filter-chip shadow-sm" onclick="filterTasks('Pending', this)">Pending</span>
            <span class="filter-chip shadow-sm" onclick="filterTasks('In Progress', this)">In Progress</span>
            <span class="filter-chip shadow-sm" onclick="filterTasks('Approval for Completed', this)">Awaiting approval</span>
            <span class="filter-chip shadow-sm" onclick="filterTasks('Completed', this)">Completed</span>
        </div>

        <h6 class="ecl-section-title mb-3 px-1">Tasks & Operations Directory</h6>
        <div id="taskListContainer">
            <p class="text-center text-muted my-5 py-5 bg-white rounded-4 border shadow-sm">Loading tasks...</p>
        </div>
    </div>

    <!-- 3. TEAM TAB SECTION -->
    <div id="sec-team" class="tab-content-sec d-none">
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="card border-0 shadow-sm text-center p-4 rounded-4 bg-white">
                    <small class="text-muted fw-bold d-block text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Total Workforce</small>
                    <h3 class="fw-bold m-0 text-dark" id="statTeamTotal">0</h3>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm text-center p-4 rounded-4 bg-white">
                    <small class="text-muted fw-bold d-block text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Active Departments</small>
                    <h3 class="fw-bold m-0 text-dark" id="statDeptsTotal">0</h3>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6">
                <button class="btn text-white w-100 py-3 shadow-sm fw-semibold rounded-4" style="background-color: #202a33; font-size: 13.5px;" onclick="openAddEmployeeModal()"><i class="bi bi-person-plus-fill me-2"></i> Add Employee</button>
            </div>
            <div class="col-6">
                <button class="btn btn-outline-secondary w-100 py-3 shadow-sm bg-white fw-semibold text-dark rounded-4" style="border-color: #dfe4e9; font-size: 13.5px;" onclick="openAddDeptModal()"><i class="bi bi-building-add me-2 text-warning"></i> New Dept</button>
            </div>
        </div>

        <h6 class="ecl-section-title mb-3 px-1">Team Directory</h6>
        <div id="employeeListContainer">
            <p class="text-center text-muted my-5 py-5 bg-white rounded-4 border shadow-sm">Loading team members...</p>
        </div>
    </div>
    @include('components.modal-add-employee')
    @include('components.modal-add-dept')
</div>
@endsection

@push('scripts')
<script>
    window.eclViewRole = 'admin';
    let allTasks = [];
    let allEmployees = [];
    let currentFilter = 'all';

    document.addEventListener('DOMContentLoaded', async () => {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.replace('/login');
            return;
        }

        await Promise.all([
            fetchDashboardStats(),
            fetchTasks(),
            fetchEmployees()
        ]);
    });

    function getAuthHeaders() {
        const token = localStorage.getItem('auth_token');
        return {
            'Authorization': 'Bearer ' + (token || ''),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };
    }

    async function fetchDashboardStats() {
        try {
            const res = await apiFetch('/api/dashboard');
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Unable to load dashboard statistics.');
            const stats = data.stats || {};
            document.getElementById('statOverviewTotal').textContent = stats.total || 0;
            document.getElementById('statPendingCount').textContent = stats.pending || 0;
            document.getElementById('statInProgressCount').textContent = stats.in_progress || 0;
            document.getElementById('statCompletedCount').textContent = stats.completed || 0;
            
            if (data.active_workforce !== undefined) {
                document.getElementById('statTeamTotal').textContent = data.active_workforce;
            }
            document.getElementById('statDeptsTotal').textContent = (data.tasks_by_department || []).length;
        } catch (err) {
            console.error('Error fetching dashboard stats:', err);
        }
    }

    async function fetchTasks() {
        try {
            const res = await apiFetch('/api/tasks');
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Unable to load tasks.');
            allTasks = Array.isArray(data) ? data : (data.tasks || []);
            renderTaskList(filterVisibleTasks());
        } catch (err) {
            document.getElementById('taskListContainer').innerHTML = `<p class="alert alert-danger">${escapeHtml(err.message)}</p>`;
        }
    }

    function renderTaskList(tasks) {
        const container = document.getElementById('taskListContainer');
        if (!tasks || tasks.length === 0) {
            container.innerHTML = `<p class="text-center text-muted my-4">No tasks found.</p>`;
            return;
        }

        container.innerHTML = tasks.map(t => {
            const statusText = t.status || 'Pending';
            const badgeClass = statusText === 'Completed' ? 'bg-success-subtle text-success-emphasis border border-success-subtle'
                : statusText === 'In Progress' ? 'bg-primary-subtle text-primary-emphasis border border-primary-subtle'
                : statusText === 'Approval for Completed' ? 'bg-info-subtle text-info-emphasis border border-info-subtle'
                : 'bg-warning-subtle text-warning-emphasis border border-warning-subtle';
            return `
                <button type="button" class="card text-start border-0 shadow-sm mb-3 p-3 rounded-4 bg-white w-100" onclick="openTaskDetails(${Number(t.id)})">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 15px;">${escapeHtml(t.title)}</h6>
                            <p class="text-muted mb-2 small" style="font-size: 12.5px;">${escapeHtml(t.description || 'No description provided.')}</p>
                        </div>
                        <span class="badge ${badgeClass} px-3 py-1 rounded-pill" style="font-size: 11px; font-weight: 600;">${escapeHtml(statusText)}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center text-muted pt-2.5 border-top mt-2" style="font-size: 12px;">
                        <span><i class="bi bi-person-fill me-1 text-dark"></i>${escapeHtml(t.assigned_employee || 'Unassigned')}</span>
                        <span><i class="bi bi-calendar-event me-1 text-warning"></i>${escapeHtml(t.due_date || 'No deadline')}</span>
                    </div>
                </button>
            `;
        }).join('');
    }

    const searchInputEl = document.getElementById('searchInput');
    if (searchInputEl) {
        searchInputEl.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            renderTaskList(filterVisibleTasks(query));
        });
    }

    function filterVisibleTasks(query = (document.getElementById('searchInput')?.value || '').toLowerCase()) {
        return allTasks.filter(task => {
            const matchesQuery = `${task.title || ''} ${task.description || ''}`.toLowerCase().includes(query);
            return matchesQuery && (currentFilter === 'all' || task.status === currentFilter);
        });
    }

    function filterTasks(status, el) {
        currentFilter = status;
        document.querySelectorAll('.filter-chip').forEach(c => {
            c.classList.remove('active');
        });
        if (el) {
            el.classList.add('active');
        }

        renderTaskList(filterVisibleTasks());
    }

    async function fetchEmployees() {
        try {
            const res = await apiFetch('/api/employees');
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Unable to load team members.');
            allEmployees = Array.isArray(data) ? data : (data.employees || []);
            document.getElementById('statTeamTotal').textContent = allEmployees.length;
            renderEmployeeList(allEmployees);
        } catch (err) {
            document.getElementById('employeeListContainer').innerHTML = `<p class="alert alert-danger">${escapeHtml(err.message)}</p>`;
        }
    }

    function renderEmployeeList(employees) {
        const container = document.getElementById('employeeListContainer');
        if (!employees || employees.length === 0) {
            container.innerHTML = `<p class="text-center text-muted my-4">No team members found.</p>`;
            return;
        }

        container.innerHTML = employees.map(emp => `
            <div class="card border-0 shadow-sm mb-3 p-3 rounded-4 bg-white d-flex align-items-center justify-content-between flex-row gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-light text-dark fw-bold border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 16px;">${escapeHtml((emp.name || 'E').charAt(0).toUpperCase())}</div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">${escapeHtml(emp.name)}</h6>
                        <small class="text-muted d-block" style="font-size: 12px;">${escapeHtml(emp.designation || 'Employee')} &bull; ${escapeHtml(emp.department || 'General')} &bull; ${escapeHtml(emp.email || '')}</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge ${emp.status === 'inactive' ? 'text-bg-secondary' : 'text-bg-success'} rounded-pill">${escapeHtml(emp.status || 'active')}</span>
                    <button class="btn btn-sm btn-outline-secondary" type="button" title="${emp.status === 'inactive' ? 'Activate' : 'Deactivate'} account"
                        onclick="toggleEmployeeStatus(${Number(emp.id)}, '${emp.status === 'inactive' ? 'active' : 'inactive'}')">
                        <i class="bi ${emp.status === 'inactive' ? 'bi-person-check' : 'bi-person-dash'}"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" type="button" title="Remove employee"
                        onclick="removeEmployee(${Number(emp.id)})"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `).join('');
    }

    function switchTab(tabName) {
        document.querySelectorAll('.tab-content-sec').forEach(sec => sec.classList.add('d-none'));
        const target = document.getElementById('sec-' + tabName);
        if (target) target.classList.remove('d-none');

        document.querySelectorAll('.sidebar-link').forEach(link => link.classList.remove('active'));
        const activeLink = document.getElementById('nav-' + tabName);
        if (activeLink) activeLink.classList.add('active');
    }

    function logoutUser() {
        localStorage.clear();
        window.location.href = '/login';
    }

    function openCreateTaskModal() {
        window.location.href = "{{ route('admin.assign-task') }}";
    }

    function openAddEmployeeModal() {
        loadEmployeeDepartments();
        new bootstrap.Modal(document.getElementById('addEmpModal')).show();
    }

    function openAddDeptModal() {
        new bootstrap.Modal(document.getElementById('addDeptModal')).show();
    }

    async function loadEmployeeDepartments() {
        const select = document.getElementById('empDeptSelect');
        try {
            const response = await apiFetch('/api/departments');
            const departments = await response.json();
            if (!response.ok) throw new Error(departments.message || 'Unable to load departments.');
            select.innerHTML = '<option value="">Select Department...</option>' + departments.map(department =>
                `<option value="${Number(department.id)}">${escapeHtml(department.name)}</option>`
            ).join('');
        } catch (error) {
            select.innerHTML = '<option value="">Departments could not be loaded</option>';
            console.error(error);
        }
    }

    async function toggleEmployeeStatus(employeeId, status) {
        try {
            const response = await apiFetch(`/api/employees/${employeeId}`, {
                method: 'PUT',
                body: JSON.stringify({ status })
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Unable to update employee status.');
            await fetchEmployees();
        } catch (error) {
            window.alert(error.message);
        }
    }

    async function removeEmployee(employeeId) {
        if (!window.confirm('Remove this employee account? This cannot be undone.')) return;
        try {
            const response = await apiFetch(`/api/employees/${employeeId}`, { method: 'DELETE' });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Unable to remove employee.');
            await fetchEmployees();
        } catch (error) {
            window.alert(error.message);
        }
    }

    function fetchEmployeesAndDepts() {
        fetchEmployees();
        fetchDashboardStats();
        loadEmployeeDepartments();
    }
</script>
@endpush