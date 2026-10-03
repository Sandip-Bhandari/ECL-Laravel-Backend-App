@extends('layouts.app')

@section('content')
<div class="app-card mb-3">
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-file-earmark-text text-primary fs-5"></i>
        <h6 class="fw-bold m-0">Task Information</h6>
    </div>

    <form id="assignTaskPageForm">
        <div class="mb-3">
            <label class="form-label small fw-bold">Task Subject / Title *</label>
            <div class="input-group">
                <span class="input-group-text app-input border-end-0 bg-transparent pe-0">✏️</span>
                <input type="text" id="pageTaskTitle" class="form-control app-input border-start-0" placeholder="e.g. Enter your task title" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Target Department *</label>
            <div class="input-group">
                <span class="input-group-text app-input border-end-0 bg-transparent pe-0">🏢</span>
                <select id="pageDeptSelect" class="form-select app-input border-start-0" required>
                    <option value="">Select Department</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Assign To Employee *</label>
            <div class="input-group">
                <span class="input-group-text app-input border-end-0 bg-transparent pe-0"><i class="bi bi-person-fill text-primary"></i></span>
                <select id="pageEmpSelect" class="form-select app-input border-start-0" required>
                    <option value="">Select Employee</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Target Completion Date *</label>
            <div class="input-group">
                <span class="input-group-text app-input border-end-0 bg-transparent pe-0">📅</span>
                <input type="date" id="pageTaskDueDate" class="form-control app-input border-start-0" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Priority *</label>
            <div class="input-group">
                <span class="input-group-text app-input border-end-0 bg-transparent pe-0">🚩</span>
                <select id="pageTaskPriority" class="form-select app-input border-start-0">
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Low">Low</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Directive Instructions / Description *</label>
            <div class="position-relative">
                <textarea id="pageTaskDesc" class="form-control app-input ps-5" rows="4" placeholder="Enter detailed operational guidelines..." required></textarea>
                <span class="position-absolute top-0 start-0 m-3 fs-6">📝</span>
            </div>
            <div class="text-end text-muted small mt-1">0/1000</div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold">Attach Document / File (Optional)</label>
            <div class="app-input text-center py-3" style="cursor: pointer;">
                <i class="bi bi-paperclip me-1"></i> <strong>Choose File to Attach</strong>
            </div>
        </div>

        <button type="submit" class="btn btn-app-dark w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-box-arrow-up"></i> Submit & Issue Task Directive
        </button>
    </form>
</div>
@endsection