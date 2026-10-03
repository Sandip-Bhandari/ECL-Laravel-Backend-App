@extends('layouts.app')

@section('content')
<div class="app-card mb-3">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h5 class="fw-bold m-0" id="detailTaskTitle">Loading Task...</h5>
            <small class="text-muted" id="detailTaskMeta">Created --</small>
        </div>
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill" id="detailTaskBadge">Pending</span>
    </div>

    <div class="mb-3">
        <label class="form-label small fw-bold text-muted">Directive Description</label>
        <p class="p-3 rounded bg-light border text-dark" id="detailTaskDesc">...</p>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-6">
            <small class="text-muted d-block fw-bold" style="font-size: 11px;">ASSIGNED TO</small>
            <span class="fw-bold text-dark" id="detailAssignedEmp">--</span>
        </div>
        <div class="col-6">
            <small class="text-muted d-block fw-bold" style="font-size: 11px;">DUE DATE</small>
            <span class="fw-bold text-danger" id="detailDueDate">--</span>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-app-dark w-100 py-2" onclick="openChatFromDetail()"><i class="bi bi-chat-dots-fill me-1"></i> Open Task Chat</button>
        <button class="btn btn-outline-danger w-100 py-2 fw-bold" onclick="deleteTaskFromDetail()"><i class="bi bi-trash-fill me-1"></i> Delete Task</button>
    </div>
</div>
@endsection