@extends('layouts.app')

@section('content')
<style>
    .emp-dashboard-wrap {
        color: #17283a;
    }
    .emp-stat-card {
        cursor: pointer;
        background: rgba(255, 255, 255, 0.94) !important;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.9) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .emp-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 26px rgba(15, 23, 42, 0.14) !important;
        border-color: rgba(229, 183, 46, 0.8) !important;
    }
    .emp-stat-card.is-selected {
        border-color: #202a33 !important;
        box-shadow: 0 0 0 2px rgba(32, 42, 51, 0.16), 0 12px 26px rgba(15, 23, 42, 0.14) !important;
    }
    .emp-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .emp-directory-card {
        background: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.55) !important;
    }
    .task-card {
        position: relative;
        overflow: hidden;
        background: #ffffff !important;
        border: 1px solid rgba(226, 232, 240, 0.95) !important;
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }
    .task-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #eab308;
    }
    .task-card.status-completed::before { background: #198754; }
    .task-card.status-in-progress::before { background: #0d6efd; }
    .task-card.status-approval::before { background: #0dcaf0; }
    .task-card.status-overdue::before { background: #dc3545; }
    .task-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.12) !important;
        border-color: rgba(148, 163, 184, 0.65) !important;
    }
    .task-desc-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .task-open-pill {
        color: #17283a;
        font-weight: 600;
        transition: color 0.2s ease, transform 0.2s ease;
    }
    .task-card:hover .task-open-pill {
        color: #0d6efd;
        transform: translateX(2px);
    }
</style>

<div class="emp-dashboard-wrap">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1">My Tasks</h3>
            <p class="text-white-50 mb-0">Review assignments, update progress, and discuss each task.</p>
        </div>
        <button class="btn btn-light rounded-pill px-4 py-2 shadow-sm fw-semibold" type="button" onclick="openNotificationsModal()">
            <i class="bi bi-bell me-1.5"></i> Notifications
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 emp-stat-card is-selected" data-stat-filter="all" role="button" tabindex="0" onclick="selectFilterFromCard('all')">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted fw-semibold">All tasks</small>
                    <span class="emp-stat-icon bg-secondary-subtle text-dark">
                        <i class="bi bi-layers"></i>
                    </span>
                </div>
                <strong class="fs-3 text-dark lh-1" id="employeeTaskTotal">0</strong>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 emp-stat-card" data-stat-filter="Pending" role="button" tabindex="0" onclick="selectFilterFromCard('Pending')">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted fw-semibold">Pending</small>
                    <span class="emp-stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </span>
                </div>
                <strong class="fs-3 text-warning lh-1" id="employeeTaskPending">0</strong>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 emp-stat-card" data-stat-filter="In Progress" role="button" tabindex="0" onclick="selectFilterFromCard('In Progress')">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted fw-semibold">In progress</small>
                    <span class="emp-stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-arrow-repeat"></i>
                    </span>
                </div>
                <strong class="fs-3 text-primary lh-1" id="employeeTaskProgress">0</strong>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 emp-stat-card" data-stat-filter="Completed" role="button" tabindex="0" onclick="selectFilterFromCard('Completed')">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted fw-semibold">Completed</small>
                    <span class="emp-stat-icon bg-success-subtle text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </span>
                </div>
                <strong class="fs-3 text-success lh-1" id="employeeTaskCompleted">0</strong>
            </div>
        </div>
    </div>

    <section id="sec-tasks">
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 emp-directory-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Task directory</h5>
                    <small class="text-muted" id="employeeTaskCountLabel">Showing all tasks</small>
                </div>
                <div class="position-relative w-100" style="max-width: 340px;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted small"></i>
                    <input id="employeeTaskSearch" class="form-control ps-5 rounded-pill bg-white" type="search" placeholder="Search tasks...">
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 mb-4" role="group" aria-label="Filter tasks">
                <button type="button" class="btn btn-sm rounded-pill px-3 btn-dark employee-filter active" data-status="all">All</button>
                <button type="button" class="btn btn-sm rounded-pill px-3 btn-outline-secondary employee-filter" data-status="Pending">Pending</button>
                <button type="button" class="btn btn-sm rounded-pill px-3 btn-outline-secondary employee-filter" data-status="In Progress">In progress</button>
                <button type="button" class="btn btn-sm rounded-pill px-3 btn-outline-secondary employee-filter" data-status="Approval for Completed">Awaiting approval</button>
                <button type="button" class="btn btn-sm rounded-pill px-3 btn-outline-secondary employee-filter" data-status="Completed">Completed</button>
                <button type="button" class="btn btn-sm rounded-pill px-3 btn-outline-danger employee-filter" data-status="Overdue">Overdue</button>
            </div>
            <div id="employeeTaskList" class="row g-3">
                <div class="col-12"><p class="text-center text-muted py-5 mb-0">Loading tasks...</p></div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    window.eclViewRole = 'employee';
    let employeeTasks = [];
    let employeeFilter = 'all';

    document.addEventListener('DOMContentLoaded', () => {
        if (!localStorage.getItem('auth_token')) {
            window.location.replace('/login');
            return;
        }
        fetchTasks();
        document.getElementById('employeeTaskSearch').addEventListener('input', renderEmployeeTasks);
        document.querySelectorAll('.employee-filter').forEach(button => button.addEventListener('click', () => {
            setActiveFilter(button.dataset.status, false);
        }));
        document.querySelectorAll('.emp-stat-card').forEach(card => {
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selectFilterFromCard(card.dataset.statFilter);
                }
            });
        });
    });

    function selectFilterFromCard(status) {
        setActiveFilter(status, true);
    }

    function setActiveFilter(status, shouldScroll = false) {
        employeeFilter = status || 'all';
        document.querySelectorAll('.employee-filter').forEach(item => {
            const isMatch = item.dataset.status === employeeFilter;
            const isOverdueBtn = item.dataset.status === 'Overdue';
            item.classList.toggle('active', isMatch);
            item.classList.toggle('btn-dark', isMatch && !isOverdueBtn);
            item.classList.toggle('btn-outline-secondary', !isMatch && !isOverdueBtn);
            item.classList.toggle('btn-danger', isMatch && isOverdueBtn);
            item.classList.toggle('btn-outline-danger', !isMatch && isOverdueBtn);
        });
        document.querySelectorAll('.emp-stat-card').forEach(card => {
            card.classList.toggle('is-selected', card.dataset.statFilter === employeeFilter);
        });
        renderEmployeeTasks();
        if (shouldScroll) {
            document.getElementById('sec-tasks')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function resetTaskFilters() {
        const searchEl = document.getElementById('employeeTaskSearch');
        if (searchEl) searchEl.value = '';
        setActiveFilter('all', false);
    }

    function getLocalTodayStr() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function isEmployeeTaskOverdue(task) {
        const status = String(task?.status || '').trim().toLowerCase();
        if (status === 'completed') return false;
        const dueDate = String(task?.due_date || '').slice(0, 10);
        if (!/^\d{4}-\d{2}-\d{2}$/.test(dueDate)) return false;
        return dueDate < getLocalTodayStr();
    }

    function getTaskAccentClass(status, overdue) {
        if (overdue) return 'status-overdue';
        const normalized = String(status || '').trim().toLowerCase();
        if (normalized === 'completed') return 'status-completed';
        if (normalized === 'in progress') return 'status-in-progress';
        if (normalized === 'approval for completed') return 'status-approval';
        return '';
    }

    async function fetchTasks() {
        const container = document.getElementById('employeeTaskList');
        try {
            const response = await apiFetch('/api/employee/tasks');
            const tasks = await response.json();
            if (!response.ok) throw new Error(tasks.message || 'Unable to load your tasks.');
            employeeTasks = Array.isArray(tasks) ? tasks : (tasks.data || []);
            document.getElementById('employeeTaskTotal').textContent = employeeTasks.length;
            document.getElementById('employeeTaskPending').textContent = employeeTasks.filter(task => task.status === 'Pending').length;
            document.getElementById('employeeTaskProgress').textContent = employeeTasks.filter(task => task.status === 'In Progress').length;
            document.getElementById('employeeTaskCompleted').textContent = employeeTasks.filter(task => task.status === 'Completed').length;
            renderEmployeeTasks();
        } catch (error) {
            container.innerHTML = `<div class="col-12"><p class="alert alert-danger mb-0">${escapeHtml(error.message)}</p></div>`;
        }
    }

    function renderEmployeeTasks() {
        const query = document.getElementById('employeeTaskSearch').value.trim().toLowerCase();
        const tasks = employeeTasks.filter(task => {
            const matchesStatus = employeeFilter === 'all'
                ? true
                : (employeeFilter === 'Overdue' ? isEmployeeTaskOverdue(task) : task.status === employeeFilter);
            const matchesQuery = `${task.title || ''} ${task.description || ''}`.toLowerCase().includes(query);
            return matchesStatus && matchesQuery;
        });

        const countLabel = document.getElementById('employeeTaskCountLabel');
        if (countLabel) {
            const filterText = employeeFilter === 'all' ? 'all tasks' : `${employeeFilter.toLowerCase()} tasks`;
            countLabel.textContent = `Showing ${tasks.length} ${filterText}`;
        }

        const container = document.getElementById('employeeTaskList');
        if (!tasks.length) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                    <p class="text-muted mb-3">No matching tasks found.</p>
                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" onclick="resetTaskFilters()">Reset filters</button>
                </div>`;
            return;
        }

        container.innerHTML = tasks.map(task => {
            const overdue = isEmployeeTaskOverdue(task);
            const accentClass = getTaskAccentClass(task.status, overdue);
            const dueText = task.due_date ? escapeHtml(task.due_date) : 'No deadline';
            return `
            <div class="col-12 col-xl-6">
                <article class="card h-100 rounded-4 shadow-sm border-0 task-card ${accentClass}" role="button" tabindex="0"
                    onclick="openTaskDetails(${Number(task.id)})" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openTaskDetails(${Number(task.id)})}">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <h6 class="fw-bold mb-0 text-dark">${escapeHtml(task.title || 'Untitled task')}</h6>
                            <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                                ${overdue ? '<span class="badge rounded-pill bg-danger-subtle text-danger-emphasis border border-danger-subtle">Overdue</span>' : ''}
                                <span class="badge rounded-pill ${getTaskStatusClass(task.status)}">${escapeHtml(task.status || 'Pending')}</span>
                            </div>
                        </div>
                        <p class="small text-muted mb-3 task-desc-clamp">${escapeHtml(task.description || 'No description provided.')}</p>
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 border-top pt-3 mt-auto small text-muted">
                            <span><i class="bi bi-person me-1"></i>${escapeHtml(task.created_by_name || 'Task manager')}</span>
                            <span class="${overdue ? 'text-danger fw-semibold' : ''}"><i class="bi bi-calendar-event me-1"></i>${dueText}</span>
                            <span class="task-open-pill"><i class="bi bi-chat-dots me-1"></i>Open discussion <i class="bi bi-chevron-right small"></i></span>
                        </div>
                    </div>
                </article>
            </div>`;
        }).join('');
    }
</script>
@endpush