@extends('layouts.app')

@section('content')
    <!-- 1. OVERVIEW TAB SECTION -->
    <div id="sec-overview" class="tab-content-sec">
        <div class="banner-card d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box-gold m-0 fs-4"><i class="bi bi-shield-shaded"></i></div>
                <div>
                    <h6 class="fw-bold mb-0">Welcome Back, <span id="userNameText">User</span>!</h6>
                    <small class="text-muted">Monitor overall performance metrics and system activities.</small>
                </div>
            </div>
        </div>

        <div class="app-card mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold m-0">Tasks Overview</h6>
                <small class="text-muted fw-bold">Realtime</small>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="donut-chart">
                    <div class="donut-inner">
                        <span class="fw-bold fs-5" id="statOverviewTotal">0</span>
                        <small class="text-muted" style="font-size: 9px;">Total Tasks</small>
                    </div>
                </div>
                <div class="flex-grow-1" style="font-size: 13px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="bi bi-circle-fill text-warning me-1"></i> Pending</span>
                        <span class="fw-bold" id="statPendingCount">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="bi bi-circle-fill text-primary me-1"></i> In Progress</span>
                        <span class="fw-bold" id="statInProgressCount">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="bi bi-circle-fill text-success me-1"></i> Completed</span>
                        <span class="fw-bold" id="statCompletedCount">0</span>
                    </div>
                </div>
            </div>
        </div>

        <h6 class="fw-bold mb-2">Quick Actions</h6>
        <div class="row g-2 mb-3">
            <div class="col-4">
                <div class="action-card" onclick="openCreateTaskModal()">
                    <div class="icon-box-gold"><i class="bi bi-plus-lg"></i></div>
                    <small class="fw-bold d-block">New Task</small>
                </div>
            </div>
            <div class="col-4">
                <div class="action-card" onclick="switchTab('tasks')">
                    <div class="icon-box-blue"><i class="bi bi-list-task"></i></div>
                    <small class="fw-bold d-block">All Tasks</small>
                </div>
            </div>
            <div class="col-4">
                <div class="action-card" onclick="switchTab('team')">
                    <div class="icon-box-green"><i class="bi bi-people-fill"></i></div>
                    <small class="fw-bold d-block">View Team</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. TASKS TAB SECTION -->
    <div id="sec-tasks" class="tab-content-sec d-none">
        <div class="app-card text-white d-flex justify-content-between align-items-center mb-3" style="background-color: var(--app-dark) !important;">
            <div>
                <h6 class="fw-bold m-0">Assign New Task</h6>
                <small class="opacity-75">Allocate new responsibilities & set deadlines</small>
            </div>
            <button class="btn btn-light rounded-circle fw-bold p-2 lh-1" onclick="openCreateTaskModal()"><i class="bi bi-plus-lg fs-5"></i></button>
        </div>

        <div class="position-relative mb-3">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" id="searchInput" class="form-control app-input ps-5" placeholder="Search tasks...">
        </div>

        <div class="d-flex gap-2 overflow-auto pb-2 mb-3">
            <span class="filter-chip active" onclick="filterTasks('all', this)">All</span>
            <span class="filter-chip" onclick="filterTasks('pending', this)">Pending</span>
            <span class="filter-chip" onclick="filterTasks('in_progress', this)">In Progress</span>
            <span class="filter-chip" onclick="filterTasks('completed', this)">Completed</span>
        </div>

        <h6 class="fw-bold mb-2">Tasks & Operations</h6>
        <div id="taskListContainer">
            <p class="text-center text-muted my-4">Loading tasks...</p>
        </div>
    </div>

    <!-- 3. TEAM TAB SECTION -->
    <div id="sec-team" class="tab-content-sec d-none">
        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="app-card text-center p-2 mb-0">
                    <small class="text-muted fw-bold d-block" style="font-size: 10px;">TOTAL TEAM</small>
                    <h5 class="fw-bold m-0" id="statTeamTotal">0</h5>
                </div>
            </div>
            <div class="col-6">
                <div class="app-card text-center p-2 mb-0">
                    <small class="text-muted fw-bold d-block" style="font-size: 10px;">DEPARTMENTS</small>
                    <h5 class="fw-bold m-0" id="statDeptsTotal">0</h5>
                </div>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <button class="btn btn-app-dark w-100 py-2 small" onclick="openAddEmployeeModal()"><i class="bi bi-person-plus me-1"></i> Add Employee</button>
            </div>
            <div class="col-6">
                <button class="btn btn-outline-secondary w-100 py-2 small bg-white fw-bold" onclick="openAddDeptModal()"><i class="bi bi-building me-1"></i> New Dept</button>
            </div>
        </div>

        <h6 class="fw-bold mb-2">Team Members</h6>
        <div id="employeeListContainer">
            <p class="text-center text-muted my-4">Loading team members...</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('searchInput').addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        const filtered = allTasks.filter(t => t.title.toLowerCase().includes(query) || (t.description && t.description.toLowerCase().includes(query)));
        renderTaskList(filtered);
    });
</script>
@endpush