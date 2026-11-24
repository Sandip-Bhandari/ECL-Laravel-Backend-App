@extends('layouts.app')

@section('content')
<style>
    .adm-detail-container {
        max-width: 860px;
        margin: 0 auto;
        color: #17283a;
    }
    .adm-top-nav-strip {
        background: rgba(18, 24, 31, 0.72);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 16px;
        padding: 11px 16px;
    }
    .adm-surface-card {
        background: rgba(255, 255, 255, 0.96) !important;
        backdrop-filter: blur(16px);
        border: 1px solid rgba(226, 232, 240, 0.95) !important;
        border-radius: 22px !important;
        box-shadow: 0 18px 44px rgba(15, 23, 42, 0.18) !important;
        overflow: hidden;
    }
    .adm-accent-line {
        height: 4px;
        width: 100%;
        background: linear-gradient(90deg, #17283a 0%, #e5b72e 55%, #075e54 100%);
    }
    .adm-directive-panel {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #17283a;
        border-radius: 14px;
        padding: 16px 18px;
        font-size: 14px;
        line-height: 1.65;
        min-height: 86px;
        white-space: pre-line;
    }
    .adm-meta-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px 16px;
        height: 100%;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .adm-meta-box:hover {
        transform: translateY(-2px);
        border-color: #cbd5e1;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.07);
    }
    .adm-meta-badge-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .adm-footer-actions {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 16px 24px;
    }
</style>

<div class="container py-3 adm-detail-container">
    <div class="adm-top-nav-strip d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <a href="/admin/dashboard" class="btn btn-light btn-sm rounded-pill px-3 py-1.5 fw-semibold shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
        <div class="d-flex align-items-center gap-2">
            <span class="text-white-50 small d-none d-sm-inline">Task Status:</span>
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-semibold" id="detailTaskBadge">Pending</span>
        </div>
    </div>

    <div class="card border-0 adm-surface-card mb-4">
        <div class="adm-accent-line"></div>

        <div class="card-body p-3 p-md-4 p-lg-5">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <span class="badge rounded-pill bg-dark text-white mb-2 px-2.5 py-1" style="font-size: 10.5px; letter-spacing: 0.5px;">
                        <i class="bi bi-shield-check me-1 text-warning"></i> ADMIN DIRECTIVE
                    </span>
                    <h4 class="fw-bold text-dark mb-1" id="detailTaskTitle">Loading Task...</h4>
                    <small class="text-muted d-inline-flex align-items-center gap-1" id="detailTaskMeta">
                        <i class="bi bi-clock"></i> Created --
                    </small>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-uppercase text-muted mb-2" style="letter-spacing: 0.6px; font-size: 11px;">
                    <i class="bi bi-file-earmark-text me-1"></i> Directive Description
                </label>
                <div class="adm-directive-panel text-dark" id="detailTaskDesc">...</div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <div class="adm-meta-box d-flex align-items-center gap-3">
                        <span class="adm-meta-badge-icon bg-primary-subtle text-primary">
                            <i class="bi bi-person-check-fill"></i>
                        </span>
                        <div class="min-w-0">
                            <small class="text-muted d-block fw-bold" style="font-size: 10.5px; letter-spacing: 0.6px;">ASSIGNED TO</small>
                            <span class="fw-bold text-dark text-truncate d-block" id="detailAssignedEmp">--</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="adm-meta-box d-flex align-items-center gap-3">
                        <span class="adm-meta-badge-icon bg-danger-subtle text-danger">
                            <i class="bi bi-calendar-event-fill"></i>
                        </span>
                        <div class="min-w-0">
                            <small class="text-muted d-block fw-bold" style="font-size: 10.5px; letter-spacing: 0.6px;">DUE DATE</small>
                            <span class="fw-bold text-danger text-truncate d-block" id="detailDueDate">--</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="adm-footer-actions">
            <div class="d-flex flex-column flex-sm-row gap-2.5">
                <button type="button" class="btn btn-app-dark w-100 py-2.5 rounded-3 fw-semibold shadow-sm" onclick="openChatFromDetail()">
                    <i class="bi bi-chat-dots-fill me-1.5"></i> Open Task Chat
                </button>
                <button type="button" class="btn btn-outline-danger w-100 py-2.5 rounded-3 fw-bold bg-white" onclick="deleteTaskFromDetail()">
                    <i class="bi bi-trash-fill me-1.5"></i> Delete Task
                </button>
            </div>
        </div>
    </div>
</div>
@endsection