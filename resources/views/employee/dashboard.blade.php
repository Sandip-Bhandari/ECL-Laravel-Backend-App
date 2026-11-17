@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-white mb-1">My Tasks</h3>
        <p class="text-white-50 mb-0">Review assignments, update progress, and discuss each task.</p>
    </div>
    <button class="btn btn-light rounded-pill" type="button" onclick="openNotificationsModal()">
        <i class="bi bi-bell me-1"></i> Notifications
    </button>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="card border-0 shadow-sm rounded-4 p-3"><small class="text-muted">All tasks</small><strong class="fs-3" id="employeeTaskTotal">0</strong></div></div>
    <div class="col-6 col-lg-3"><div class="card border-0 shadow-sm rounded-4 p-3"><small class="text-muted">Pending</small><strong class="fs-3 text-warning" id="employeeTaskPending">0</strong></div></div>
    <div class="col-6 col-lg-3"><div class="card border-0 shadow-sm rounded-4 p-3"><small class="text-muted">In progress</small><strong class="fs-3 text-primary" id="employeeTaskProgress">0</strong></div></div>
    <div class="col-6 col-lg-3"><div class="card border-0 shadow-sm rounded-4 p-3"><small class="text-muted">Completed</small><strong class="fs-3 text-success" id="employeeTaskCompleted">0</strong></div></div>
</div>

<section id="sec-tasks">
    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h5 class="fw-bold mb-0">Task directory</h5>
            <input id="employeeTaskSearch" class="form-control" style="max-width: 340px" type="search" placeholder="Search tasks...">
        </div>
        <div class="d-flex flex-wrap gap-2 mb-3" role="group" aria-label="Filter tasks">
            <button type="button" class="btn btn-sm btn-dark employee-filter active" data-status="all">All</button>
            <button type="button" class="btn btn-sm btn-outline-secondary employee-filter" data-status="Pending">Pending</button>
            <button type="button" class="btn btn-sm btn-outline-secondary employee-filter" data-status="In Progress">In progress</button>
            <button type="button" class="btn btn-sm btn-outline-secondary employee-filter" data-status="Approval for Completed">Awaiting approval</button>
            <button type="button" class="btn btn-sm btn-outline-secondary employee-filter" data-status="Completed">Completed</button>
        </div>
        <div id="employeeTaskList" class="row g-3">
            <div class="col-12"><p class="text-center text-muted py-5 mb-0">Loading tasks...</p></div>
        </div>
    </div>
</section>
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
            employeeFilter = button.dataset.status;
            document.querySelectorAll('.employee-filter').forEach(item => {
                item.classList.toggle('btn-dark', item === button);
                item.classList.toggle('btn-outline-secondary', item !== button);
                item.classList.toggle('active', item === button);
            });
            renderEmployeeTasks();
        }));
    });

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
            const matchesStatus = employeeFilter === 'all' || task.status === employeeFilter;
            const matchesQuery = `${task.title || ''} ${task.description || ''}`.toLowerCase().includes(query);
            return matchesStatus && matchesQuery;
        });
        const container = document.getElementById('employeeTaskList');
        if (!tasks.length) {
            container.innerHTML = '<div class="col-12"><p class="text-center text-muted py-5 mb-0">No matching tasks.</p></div>';
            return;
        }
        container.innerHTML = tasks.map(task => `
            <div class="col-12 col-xl-6">
                <article class="card h-100 rounded-4 shadow-sm border-0 task-card" role="button" tabindex="0"
                    onclick="openTaskDetails(${Number(task.id)})" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openTaskDetails(${Number(task.id)})}">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <h6 class="fw-bold mb-2">${escapeHtml(task.title || 'Untitled task')}</h6>
                            <span class="badge rounded-pill ${getTaskStatusClass(task.status)}">${escapeHtml(task.status || 'Pending')}</span>
                        </div>
                        <p class="small text-muted mb-3">${escapeHtml(task.description || 'No description provided.')}</p>
                        <div class="d-flex flex-wrap justify-content-between gap-2 border-top pt-3 small text-muted">
                            <span><i class="bi bi-person me-1"></i>${escapeHtml(task.created_by_name || 'Task manager')}</span>
                            <span><i class="bi bi-calendar-event me-1"></i>${escapeHtml(task.due_date || 'No deadline')}</span>
                            <span><i class="bi bi-chat-dots me-1"></i>Open discussion</span>
                        </div>
                    </div>
                </article>
            </div>`).join('');
    }
</script>
@endpush
