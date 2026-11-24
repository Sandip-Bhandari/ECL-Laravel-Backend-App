@extends('layouts.app')

@section('content')
<style>
    .emp-pro-page {
        color: #17283a;
    }
    .emp-pro-topbar {
        background: rgba(18, 24, 31, 0.72);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 16px;
        padding: 12px 16px;
    }
    .emp-pro-card {
        background: rgba(255, 255, 255, 0.96) !important;
        backdrop-filter: blur(16px);
        border: 1px solid rgba(226, 232, 240, 0.95) !important;
        border-radius: 22px !important;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.16) !important;
    }
    .emp-pro-hero-accent {
        height: 4px;
        width: 100%;
        background: linear-gradient(90deg, #e5b72e 0%, #0d6efd 55%, #198754 100%);
        border-radius: 22px 22px 0 0;
    }
    .emp-pro-desc {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #1e293b;
        border-radius: 14px;
        padding: 16px 18px;
        font-size: 14px;
        line-height: 1.65;
        white-space: pre-line;
    }
    .emp-pro-stat {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px 16px;
        height: 100%;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .emp-pro-stat:hover {
        transform: translateY(-2px);
        border-color: #cbd5e1;
    }
    .emp-pro-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .emp-pro-action-panel {
        background: linear-gradient(180deg, #fffdf7 0%, #fef9e7 100%);
        border: 1px solid #fde68a;
        border-radius: 16px;
        padding: 18px;
    }
</style>

<div class="container py-3 emp-pro-page">
    <div class="emp-pro-topbar d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <a href="/employee/dashboard" class="btn btn-light btn-sm rounded-pill px-3 py-1.5 fw-semibold shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to My Tasks
        </a>
        <div class="d-flex align-items-center gap-2">
            <span class="text-white-50 small d-none d-sm-inline">Current Status:</span>
            <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-semibold" id="empDetailBadge">Loading...</span>
        </div>
    </div>

    <div class="card border-0 emp-pro-card mb-4 overflow-hidden">
        <div class="emp-pro-hero-accent"></div>
        <div class="card-body p-3 p-md-4 p-lg-5">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 11px; letter-spacing: 0.8px;">
                        Task Assignment Overview
                    </div>
                    <h4 class="fw-bold text-dark mb-1" id="empDetailTitle">Loading Task...</h4>
                    <small class="text-muted d-inline-flex align-items-center gap-1" id="empDetailMeta">
                        <i class="bi bi-building"></i> Department &bull; Created --
                    </small>
                </div>
                <div class="d-flex align-items-center gap-2" id="empDetailExtraBadges"></div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-uppercase text-muted mb-2" style="letter-spacing: 0.6px; font-size: 11px;">
                    <i class="bi bi-card-text me-1"></i> Task Description & Instructions
                </label>
                <div class="emp-pro-desc text-dark" id="empDetailDesc">Loading description...</div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="emp-pro-stat d-flex align-items-center gap-3">
                        <span class="emp-pro-icon bg-primary-subtle text-primary">
                            <i class="bi bi-person-badge-fill"></i>
                        </span>
                        <div class="min-w-0">
                            <small class="text-muted d-block fw-bold" style="font-size: 10.5px; letter-spacing: 0.6px;">ASSIGNED TO</small>
                            <span class="fw-bold text-dark text-truncate d-block" id="empDetailAssignee">--</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="emp-pro-stat d-flex align-items-center gap-3">
                        <span class="emp-pro-icon bg-info-subtle text-info">
                            <i class="bi bi-flag-fill"></i>
                        </span>
                        <div class="min-w-0">
                            <small class="text-muted d-block fw-bold" style="font-size: 10.5px; letter-spacing: 0.6px;">PRIORITY LEVEL</small>
                            <span class="fw-bold text-dark text-truncate d-block" id="empDetailPriority">Medium</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="emp-pro-stat d-flex align-items-center gap-3">
                        <span class="emp-pro-icon bg-danger-subtle text-danger">
                            <i class="bi bi-calendar2-check-fill"></i>
                        </span>
                        <div class="min-w-0">
                            <small class="text-muted d-block fw-bold" style="font-size: 10.5px; letter-spacing: 0.6px;">DUE DATE</small>
                            <span class="fw-bold text-dark text-truncate d-block" id="empDetailDueDate">No deadline</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="emp-pro-action-panel">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-5">
                        <label for="empDetailStatusSelect" class="form-label small fw-bold text-dark mb-1.5">
                            <i class="bi bi-arrow-repeat me-1 text-warning"></i> Update Task Progress Status
                        </label>
                        <select id="empDetailStatusSelect" class="form-select bg-white rounded-3 py-2 shadow-none">
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Approval for Completed">Approval for Completed</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-7 d-flex flex-column flex-sm-row gap-2.5 justify-content-md-end">
                        <button type="button" class="btn btn-dark rounded-3 px-4 py-2 fw-semibold shadow-sm" id="empSaveStatusBtn" onclick="updateEmployeeTaskStatus()">
                            <i class="bi bi-check2-circle me-1"></i> Save Status
                        </button>
                        <button type="button" class="btn btn-primary rounded-3 px-4 py-2 fw-semibold shadow-sm" onclick="openEmployeeTaskChat()">
                            <i class="bi bi-chat-dots-fill me-1"></i> Open Task Chat
                        </button>
                    </div>
                </div>
                <div id="empStatusFeedback" class="small mt-2.5 d-none"></div>
            </div>
        </div>
    </div>

    @includeIf('components.modal-task-chat')
</div>
@endsection

@push('scripts')
<script>
    window.eclViewRole = 'employee';
    let currentEmployeeTask = null;

    function getTaskIdFromUrl() {
        const parts = window.location.pathname.split('/').filter(Boolean);
        const lastSegment = parts[parts.length - 1];
        if (/^\d+$/.test(lastSegment)) return Number(lastSegment);
        const params = new URLSearchParams(window.location.search);
        const qId = params.get('id') || params.get('task_id');
        return qId && /^\d+$/.test(qId) ? Number(qId) : null;
    }

    function getLocalTodayStr() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function isTaskOverdueDetail(task) {
        const status = String(task?.status || '').trim().toLowerCase();
        if (status === 'completed') return false;
        const due = String(task?.due_date || '').slice(0, 10);
        return /^\d{4}-\d{2}-\d{2}$/.test(due) && due < getLocalTodayStr();
    }

    function applyStatusBadgeStyle(badgeEl, status) {
        if (!badgeEl) return;
        const val = status || 'Pending';
        badgeEl.textContent = val;
        badgeEl.className = 'badge rounded-pill px-3 py-2 fw-semibold';
        if (val === 'Completed') {
            badgeEl.classList.add('bg-success', 'text-white');
        } else if (val === 'In Progress') {
            badgeEl.classList.add('bg-primary', 'text-white');
        } else if (val === 'Approval for Completed') {
            badgeEl.classList.add('bg-info', 'text-dark');
        } else {
            badgeEl.classList.add('bg-warning', 'text-dark');
        }
    }

    function showStatusFeedback(message, isSuccess = true) {
        const box = document.getElementById('empStatusFeedback');
        if (!box) return;
        box.classList.remove('d-none', 'text-success', 'text-danger');
        box.classList.add(isSuccess ? 'text-success' : 'text-danger', 'fw-semibold');
        box.textContent = message;
    }

    function renderEmployeeTaskDetail(task) {
        currentEmployeeTask = task;

        document.getElementById('empDetailTitle').textContent = task.title || 'Untitled Task';
        const deptName = task.department_name || task.department?.name || 'General';
        const createdText = task.created_at ? `Created ${String(task.created_at).slice(0, 10)}` : '';
        document.getElementById('empDetailMeta').innerHTML = `<i class="bi bi-building me-1"></i>${escapeHtml(createdText ? `${deptName} • ${createdText}` : deptName)}`;

        document.getElementById('empDetailDesc').textContent = task.description || 'No description provided.';
        document.getElementById('empDetailAssignee').textContent = task.assigned_employee || task.assigned_to_name || task.assignee?.name || 'Unassigned';
        document.getElementById('empDetailPriority').textContent = task.priority || 'Medium';

        const dueDateText = task.due_date ? String(task.due_date).slice(0, 10) : 'No deadline';
        const dueEl = document.getElementById('empDetailDueDate');
        dueEl.textContent = dueDateText;

        const overdue = isTaskOverdueDetail(task);
        dueEl.classList.toggle('text-danger', overdue);
        dueEl.classList.toggle('text-dark', !overdue);

        const extraBadges = document.getElementById('empDetailExtraBadges');
        extraBadges.innerHTML = overdue
            ? '<span class="badge rounded-pill bg-danger-subtle text-danger-emphasis border border-danger-subtle px-3 py-2"><i class="bi bi-exclamation-triangle-fill me-1"></i>Overdue</span>'
            : '';

        applyStatusBadgeStyle(document.getElementById('empDetailBadge'), task.status || 'Pending');

        const selectEl = document.getElementById('empDetailStatusSelect');
        if (selectEl && task.status) {
            selectEl.value = task.status;
        }
    }

    async function loadEmployeeTaskDetail() {
        const taskId = getTaskIdFromUrl();
        if (!taskId) {
            document.getElementById('empDetailTitle').textContent = 'Invalid Task ID';
            document.getElementById('empDetailDesc').textContent = 'Task identifier was not found in the URL.';
            return;
        }

        try {
            const res = await apiFetch(`/api/tasks/${taskId}`);
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Unable to load task details.');
            const task = data.task || data.data || data;
            renderEmployeeTaskDetail(task);
        } catch (err) {
            document.getElementById('empDetailTitle').textContent = 'Error Loading Task';
            document.getElementById('empDetailDesc').textContent = err.message;
        }
    }

    async function updateEmployeeTaskStatus() {
        const taskId = currentEmployeeTask?.id || getTaskIdFromUrl();
        const selectEl = document.getElementById('empDetailStatusSelect');
        const saveBtn = document.getElementById('empSaveStatusBtn');
        if (!taskId || !selectEl) return;

        const newStatus = selectEl.value;
        try {
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            }

            const res = await apiFetch(`/api/tasks/${taskId}/status`, {
                method: 'PATCH',
                body: JSON.stringify({ status: newStatus })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Failed to update status.');

            if (currentEmployeeTask) {
                currentEmployeeTask.status = newStatus;
            }
            applyStatusBadgeStyle(document.getElementById('empDetailBadge'), newStatus);
            showStatusFeedback(`Status updated to "${newStatus}".`, true);
        } catch (err) {
            showStatusFeedback(err.message, false);
        } finally {
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Save Status';
            }
        }
    }

    function openEmployeeTaskChat() {
        const taskId = currentEmployeeTask?.id || getTaskIdFromUrl();
        if (!taskId) return;
        if (typeof openTaskChat === 'function') {
            openTaskChat(taskId);
        } else if (typeof openTaskDetails === 'function') {
            openTaskDetails(taskId);
        } else {
            window.location.href = `/tasks/${taskId}/chat`;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!localStorage.getItem('auth_token')) {
            window.location.replace('/login');
            return;
        }
        loadEmployeeTaskDetail();
    });
</script>
@endpush