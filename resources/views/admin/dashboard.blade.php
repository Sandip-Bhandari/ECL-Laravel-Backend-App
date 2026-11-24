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

/* Status Donut Ring */
.status-donut-ring {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    background: conic-gradient(#EFEAE1 0% 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.35s ease;
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

/* Single-Line Task Management Banner */
.ecl-tms-page .card.tms-ops-banner {
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    justify-content: space-between !important;
    gap: 14px;
    background: rgba(18, 24, 31, 0.82) !important;
    border: 1px solid rgba(255, 255, 255, 0.14) !important;
    padding: 18px 22px !important;
}

@media (min-width: 576px) {
    .ecl-tms-page .card.tms-ops-banner {
        flex-direction: row !important;
        align-items: center !important;
    }
}

.tms-ops-icon {
    width: 44px;
    height: 44px;
    border-radius: 13px;
    background: rgba(229, 183, 46, 0.18);
    color: #e5b72e;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.tms-new-task-btn {
    background: linear-gradient(135deg, #e5b72e 0%, #b88a14 100%) !important;
    color: #12181f !important;
    border: none !important;
    border-radius: 999px !important;
    padding: 10px 20px !important;
    font-size: 13.5px !important;
    font-weight: 700 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    box-shadow: 0 6px 18px rgba(229, 183, 46, 0.25);
    transition: transform 0.18s ease, box-shadow 0.18s ease;
}

.tms-new-task-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 22px rgba(229, 183, 46, 0.38);
}

.tms-search-box .form-control {
    background: rgba(255, 255, 255, 0.95) !important;
    border: 1px solid rgba(226, 232, 240, 0.9) !important;
    border-radius: 16px !important;
    color: #0f172a !important;
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08) !important;
}

.tms-search-box .form-control:focus {
    background: #ffffff !important;
    border-color: #e5b72e !important;
    box-shadow: 0 0 0 3px rgba(229, 183, 46, 0.2) !important;
}

/* Responsive Filter Chips Row */
.tms-filter-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
}

.tms-filter-row .filter-chip { 
    border: 1px solid rgba(255, 255, 255, 0.35) !important; 
    background: rgba(255, 255, 255, 0.9) !important; 
    color: #334155 !important; 
    border-radius: 999px !important; 
    padding: 7px 15px !important; 
    font-size: 12.5px !important;
    font-weight: 600 !important; 
    cursor: pointer; 
    transition: all 0.18s ease; 
    white-space: nowrap;
}

.tms-filter-row .filter-chip:hover {
    background: #ffffff !important;
    color: #0f172a !important;
    transform: translateY(-1px);
}

.tms-filter-row .filter-chip.active { 
    background: #12181f !important; 
    border-color: #e5b72e !important; 
    color: #ffe89e !important; 
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.22);
}

/* Task Directory Cards */
.tms-dir-card {
    background: rgba(255, 255, 255, 0.96) !important;
    border: 1px solid #e2e8f0 !important;
    border-left: 4px solid #0d6efd !important;
    border-radius: 16px !important;
    padding: 15px 18px !important;
    margin-bottom: 12px !important;
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08) !important;
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}

.tms-dir-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 26px rgba(15, 23, 42, 0.14) !important;
    border-color: #cbd5e1 !important;
}

.tms-dir-card.is-completed { border-left-color: #198754 !important; }
.tms-dir-card.is-pending { border-left-color: #eab308 !important; }
.tms-dir-card.is-approval { border-left-color: #0dcaf0 !important; }
.tms-dir-card.is-overdue { border-left-color: #dc3545 !important; }

.tms-dir-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 3px;
}

.tms-dir-desc {
    font-size: 12.5px;
    color: #64748b;
    margin-bottom: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.tms-dir-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 12px;
    padding-top: 10px;
    border-top: 1px solid #f1f5f9;
    font-size: 12px;
    color: #475569;
}

/* Official Team & Workforce Styling */
.ecl-tms-page .card.team-kpi-card {
    background: rgba(255, 255, 255, 0.95) !important;
    border: 1px solid rgba(226, 232, 240, 0.95) !important;
    border-radius: 18px !important;
    padding: 20px 22px !important;
    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12) !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    position: relative;
    overflow: hidden;
}

.ecl-tms-page .card.team-kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3.5px;
}

.ecl-tms-page .card.team-kpi-workforce::before {
    background: linear-gradient(90deg, #12181f 0%, #e5b72e 100%);
}

.ecl-tms-page .card.team-kpi-depts::before {
    background: linear-gradient(90deg, #075e54 0%, #169b61 100%);
}

.ecl-tms-page .card.team-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 32px rgba(15, 23, 42, 0.16) !important;
    border-color: rgba(229, 183, 46, 0.55) !important;
}

.team-kpi-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.team-action-primary {
    background: linear-gradient(135deg, #12181f 0%, #24303d 100%) !important;
    color: #ffffff !important;
    border: 1px solid rgba(229, 183, 46, 0.35) !important;
    border-radius: 14px !important;
    padding: 13px 16px !important;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.18) !important;
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}

.team-action-primary:hover {
    transform: translateY(-2px);
    color: #ffe89e !important;
    border-color: #e5b72e !important;
    box-shadow: 0 12px 26px rgba(15, 23, 42, 0.24) !important;
}

.team-action-secondary {
    background: rgba(255, 255, 255, 0.95) !important;
    color: #0f172a !important;
    border: 1px solid rgba(226, 232, 240, 0.95) !important;
    border-radius: 14px !important;
    padding: 13px 16px !important;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.1) !important;
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease, background-color 0.18s ease;
}

.team-action-secondary:hover {
    transform: translateY(-2px);
    background: #ffffff !important;
    color: #0f172a !important;
    border-color: #e5b72e !important;
    box-shadow: 0 12px 26px rgba(15, 23, 42, 0.15) !important;
}

.team-action-broadcast {
    background: linear-gradient(135deg, #e5b72e 0%, #c99916 100%) !important;
    color: #12181f !important;
    border: 1px solid rgba(255, 255, 255, 0.45) !important;
    border-radius: 14px !important;
    padding: 13px 16px !important;
    font-size: 13.5px !important;
    font-weight: 700 !important;
    box-shadow: 0 8px 20px rgba(229, 183, 46, 0.25) !important;
    transition: transform 0.18s ease, box-shadow 0.18s ease;
}

.team-action-broadcast:hover {
    transform: translateY(-2px);
    color: #12181f !important;
    box-shadow: 0 12px 26px rgba(229, 183, 46, 0.38) !important;
}

.ecl-tms-page .card.team-member-card {
    background: rgba(255, 255, 255, 0.96) !important;
    border: 1px solid #e2e8f0 !important;
    border-left: 4px solid #169b61 !important;
    border-radius: 16px !important;
    padding: 15px 18px !important;
    margin-bottom: 12px !important;
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08) !important;
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}

.ecl-tms-page .card.team-member-card.is-inactive {
    border-left-color: #94a3b8 !important;
    opacity: 0.9;
}

.ecl-tms-page .card.team-member-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 26px rgba(15, 23, 42, 0.14) !important;
    border-color: #cbd5e1 !important;
}

.team-avatar-badge {
    width: 44px;
    height: 44px;
    border-radius: 13px;
    background: linear-gradient(135deg, #12181f 0%, #273444 100%);
    color: #ffe89e;
    border: 1px solid rgba(229, 183, 46, 0.45);
    font-size: 15px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.team-meta-strip {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    margin-top: 4px;
    font-size: 12px;
    color: #64748b;
}

.team-icon-btn {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

/* Polished Broadcast Modal Styling */
#modalBroadcast .modal-content {
    border: 1px solid #e2e8f0;
    border-radius: 22px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 26px 64px rgba(15, 23, 42, 0.32);
}

.tms-broadcast-top-bar {
    height: 4px;
    width: 100%;
    background: linear-gradient(90deg, #12181f 0%, #e5b72e 55%, #075e54 100%);
}

#modalBroadcast .modal-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 15px 20px;
}

.tms-broadcast-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: #fef3c7;
    color: #b45309;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
}

#modalBroadcast .modal-body {
    padding: 20px;
    background: #ffffff;
}

#modalBroadcast .app-input {
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 10px 13px;
    font-size: 13.5px;
    color: #0f172a;
    transition: border-color 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
}

#modalBroadcast .app-input:focus {
    background: #ffffff;
    border-color: #e5b72e;
    box-shadow: 0 0 0 3px rgba(229, 183, 46, 0.2);
}

.tms-broadcast-scope-note {
    background: #fffbeb;
    border: 1px solid #fde68a;
    color: #92400e;
    border-radius: 12px;
    padding: 9px 12px;
    font-size: 12px;
}

.btn-app-dark {
    background: linear-gradient(135deg, #12181f 0%, #24303d 100%);
    color: #ffffff;
    border: 1px solid rgba(229, 183, 46, 0.4);
    border-radius: 12px;
    transition: transform 0.18s ease, box-shadow 0.18s ease, color 0.18s ease;
}

.btn-app-dark:hover {
    color: #ffe89e;
    transform: translateY(-1px);
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.22);
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
                    <h6 class="fw-bold m-0 text-dark" style="font-size: 16px; letter-spacing: -0.2px;">Tasks Analytics &amp; Overview</h6>
                </div>
                <span class="badge bg-warning-subtle text-warning-emphasis fw-bold px-3 py-1.5 rounded-pill" style="font-size: 11px;"><i class="bi bi-circle-fill text-success me-1.5" style="font-size: 8px;"></i> Live Status</span>
            </div>
            
            <div class="row align-items-center g-4">
                <!-- Circular Total Counter & Status Donut Widget -->
                <div class="col-md-auto text-center">
                    <div class="shadow-sm mx-auto p-1 status-donut-ring" id="statDonutChart">
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

                        <div class="analytics-metric-card d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 13px;">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                </div>
                                <span class="fw-semibold text-dark" style="font-size: 14px;">Overdue Tasks</span>
                            </div>
                            <span class="fw-bold badge bg-white text-danger border px-3 py-2 shadow-sm fs-6" id="statOverdueCount">0</span>
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
                            <small class="text-muted" style="font-size: 11.5px;">Create &amp; assign task</small>
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
        <!-- Single-Line Task Management Header Banner -->
        <div class="card tms-ops-banner text-white mb-3">
            <div class="d-flex align-items-center gap-3 min-w-0">
                <div class="tms-ops-icon">
                    <i class="bi bi-journal-plus"></i>
                </div>
                <div class="min-w-0">
                    <h5 class="fw-bold mb-0.5 text-white" style="font-size: 17px; letter-spacing: 0.2px;">Task Management &amp; Operations</h5>
                    <p class="mb-0 text-truncate" style="font-size: 12px; color: rgba(255, 255, 255, 0.72);">Allocate new operational responsibilities &amp; set strict deadlines</p>
                </div>
            </div>
            <button type="button" class="btn tms-new-task-btn" onclick="openCreateTaskModal()">
                <i class="bi bi-plus-lg"></i> New Task
            </button>
        </div>

        <div class="position-relative tms-search-box mb-3">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" id="searchInput" class="form-control ps-5 py-3 shadow-none" placeholder="Search tasks by title or description..." style="font-size: 13.5px;">
        </div>

        <div class="tms-filter-row">
            <span class="filter-chip active shadow-sm" onclick="filterTasks('all', this)">All</span>
            <span class="filter-chip shadow-sm" onclick="filterTasks('Pending', this)">Pending</span>
            <span class="filter-chip shadow-sm" onclick="filterTasks('In Progress', this)">In Progress</span>
            <span class="filter-chip shadow-sm" onclick="filterTasks('Approval for Completed', this)">Awaiting approval</span>
            <span class="filter-chip shadow-sm" onclick="filterTasks('Completed', this)">Completed</span>
            <span class="filter-chip shadow-sm" onclick="filterTasks('Overdue', this)">Overdue</span>
        </div>

        <h6 class="ecl-section-title mb-3 px-1">Tasks &amp; Operations Directory</h6>
        <div id="taskListContainer">
            <p class="text-center text-muted my-5 py-5 bg-white rounded-4 border shadow-sm">Loading tasks...</p>
        </div>
    </div>

    <!-- 3. TEAM TAB SECTION -->
    <div id="sec-team" class="tab-content-sec d-none">
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <div class="card team-kpi-card team-kpi-workforce d-flex flex-row align-items-center justify-content-between gap-3 h-100">
                    <div>
                        <small class="text-muted fw-bold d-block text-uppercase mb-1" style="font-size: 10.5px; letter-spacing: 0.7px;">Total Workforce</small>
                        <h3 class="fw-bold m-0 text-dark fs-2 lh-1" id="statTeamTotal">0</h3>
                    </div>
                    <div class="team-kpi-icon" style="background: #FDF3D6; color: #B2820A;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card team-kpi-card team-kpi-depts d-flex flex-row align-items-center justify-content-between gap-3 h-100">
                    <div>
                        <small class="text-muted fw-bold d-block text-uppercase mb-1" style="font-size: 10.5px; letter-spacing: 0.7px;">Active Departments</small>
                        <h3 class="fw-bold m-0 text-dark fs-2 lh-1" id="statDeptsTotal">0</h3>
                    </div>
                    <div class="team-kpi-icon" style="background: #E8F5E9; color: #169b61;">
                        <i class="bi bi-building-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4 col-sm-6">
                <button type="button" class="btn team-action-primary w-100 d-flex align-items-center justify-content-center gap-2" onclick="openAddEmployeeModal()">
                    <i class="bi bi-person-plus-fill text-warning"></i>
                    <span>Add Employee</span>
                </button>
            </div>
            <div class="col-md-4 col-sm-6">
                <button type="button" class="btn team-action-secondary w-100 d-flex align-items-center justify-content-center gap-2" onclick="openAddDeptModal()">
                    <i class="bi bi-building-add text-warning"></i>
                    <span>New Dept</span>
                </button>
            </div>
            <div class="col-md-4 col-sm-12">
                <button type="button" class="btn team-action-broadcast w-100 d-flex align-items-center justify-content-center gap-2" onclick="openBroadcastModal()">
                    <i class="bi bi-megaphone-fill"></i>
                    <span>Broadcast Notice</span>
                </button>
            </div>
        </div>

        <h6 class="ecl-section-title mb-3 px-1"><i class="bi bi-person-lines-fill text-warning me-1.5"></i> Team Directory</h6>
        <div id="employeeListContainer">
            <p class="text-center text-muted my-5 py-5 bg-white rounded-4 border shadow-sm">Loading team members...</p>
        </div>
    </div>

    <!-- Broadcast Notice Modal (Scoped to Current Admin's Team) -->
    <div class="modal fade" id="modalBroadcast" tabindex="-1" aria-labelledby="modalBroadcastTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="tms-broadcast-top-bar"></div>
                <div class="modal-header d-flex justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                        <span class="tms-broadcast-icon-box">
                            <i class="bi bi-megaphone-fill"></i>
                        </span>
                        <div class="min-w-0">
                            <h5 class="modal-title fw-bold text-dark m-0" id="modalBroadcastTitle" style="font-size: 16px;">Publish Notice Broadcast</h5>
                            <small class="text-muted d-block text-truncate" style="font-size: 11.5px;">Send announcement only to your added team members</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="broadcastForm" class="vstack gap-3">
                        <div class="tms-broadcast-scope-note d-flex align-items-center justify-content-between gap-2">
                            <span><i class="bi bi-shield-lock-fill me-1.5"></i>Scoped to your managed team</span>
                            <span class="badge rounded-pill bg-warning text-dark" id="modalBroadcastTeamCount">0 Members</span>
                        </div>

                        <div>
                            <label for="broadcastHeading" class="form-label small fw-bold text-dark mb-1">Notice Heading</label>
                            <input type="text" id="broadcastHeading" class="form-control app-input" maxlength="160" placeholder="e.g. Mandatory Safety Briefing" required>
                        </div>

                        <div>
                            <label for="broadcastTarget" class="form-label small fw-bold text-dark mb-1">Target Audience (Within Your Team)</label>
                            <select id="broadcastTarget" class="form-select app-input">
                                <option value="all">All My Team Members</option>
                            </select>
                        </div>

                        <div>
                            <label for="broadcastContent" class="form-label small fw-bold text-dark mb-1">Announcement Content</label>
                            <textarea id="broadcastContent" class="form-control app-input" rows="3" maxlength="2000" placeholder="Type notice details..." required></textarea>
                        </div>

                        <button type="submit" id="broadcastSubmitBtn" class="btn btn-app-dark w-100 py-2.5 fw-bold mt-1">
                            <i class="bi bi-send-fill me-1.5 text-warning"></i>Publish Notice
                        </button>
                    </form>
                </div>
            </div>
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
    let webBroadcastModal;

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

    function getLocalTodayDateStr() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function isTaskOverdue(task) {
        const status = String(task?.status || '').trim().toLowerCase();
        if (status === 'completed') return false;

        const dueDateStr = String(task?.due_date || '').slice(0, 10);
        if (!/^\d{4}-\d{2}-\d{2}$/.test(dueDateStr)) return false;

        return dueDateStr < getLocalTodayDateStr();
    }

    function computeStatsFromTasks(tasks) {
        const list = Array.isArray(tasks) ? tasks : [];
        const normalize = (val) => String(val || '').trim().toLowerCase();

        return {
            total: list.length,
            pending: list.filter(t => normalize(t.status) === 'pending').length,
            in_progress: list.filter(t => ['in progress', 'in_progress'].includes(normalize(t.status))).length,
            completed: list.filter(t => normalize(t.status) === 'completed').length,
            overdue: list.filter(isTaskOverdue).length,
        };
    }

    function updateStatusDonutGraph(stats) {
        const donutEl = document.getElementById('statDonutChart');
        if (!donutEl) return;

        const pending = Math.max(0, Number(stats?.pending) || 0);
        const inProgress = Math.max(0, Number(stats?.in_progress) || 0);
        const completed = Math.max(0, Number(stats?.completed) || 0);
        const overdue = Math.max(0, Number(stats?.overdue) || 0);

        const activeOpen = Math.max(0, pending + inProgress);
        const effectiveOverdue = Math.min(overdue, activeOpen);
        const remainingOpen = Math.max(0, activeOpen - effectiveOverdue);

        const openRatio = activeOpen > 0 ? remainingOpen / activeOpen : 0;
        const onTimePending = pending * openRatio;
        const onTimeInProgress = inProgress * openRatio;

        const sum = onTimePending + onTimeInProgress + completed + effectiveOverdue;
        if (sum <= 0) {
            donutEl.style.background = 'conic-gradient(#EFEAE1 0% 100%)';
            return;
        }

        const pPct = (onTimePending / sum) * 100;
        const ipPct = (onTimeInProgress / sum) * 100;
        const cPct = (completed / sum) * 100;
        const oPct = (effectiveOverdue / sum) * 100;

        const s1 = pPct;
        const s2 = s1 + ipPct;
        const s3 = s2 + cPct;
        const s4 = Math.min(100, s3 + oPct);

        donutEl.style.background = `conic-gradient(
            #eab308 0% ${s1}%,
            #0d6efd ${s1}% ${s2}%,
            #198754 ${s2}% ${s3}%,
            #dc3545 ${s3}% ${s4}%,
            #EFEAE1 ${s4}% 100%
        )`;
    }

    function applyDashboardStats(stats, fallbackTasks = null) {
        const fallback = Array.isArray(fallbackTasks) ? computeStatsFromTasks(fallbackTasks) : null;
        const rawTotal = stats?.total;
        const hasApiStats = rawTotal !== undefined && rawTotal !== null && Number(rawTotal) > 0;

        const finalStats = hasApiStats
            ? {
                total: Number(stats.total) || 0,
                pending: Number(stats.pending) || 0,
                in_progress: Number(stats.in_progress) || 0,
                completed: Number(stats.completed) || 0,
                overdue: Number(stats.overdue ?? fallback?.overdue ?? 0) || 0,
            }
            : (fallback || {
                total: Number(stats?.total) || 0,
                pending: Number(stats?.pending) || 0,
                in_progress: Number(stats?.in_progress) || 0,
                completed: Number(stats?.completed) || 0,
                overdue: Number(stats?.overdue) || 0,
            });

        document.getElementById('statOverviewTotal').textContent = finalStats.total;
        document.getElementById('statPendingCount').textContent = finalStats.pending;
        document.getElementById('statInProgressCount').textContent = finalStats.in_progress;
        document.getElementById('statCompletedCount').textContent = finalStats.completed;

        const overdueEl = document.getElementById('statOverdueCount');
        if (overdueEl) {
            overdueEl.textContent = finalStats.overdue;
        }

        updateStatusDonutGraph(finalStats);
    }

    async function fetchDashboardStats() {
        try {
            const res = await apiFetch('/api/dashboard');
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Unable to load dashboard statistics.');

            const stats = data.stats || data.data?.stats || data.data || {};
            applyDashboardStats(stats, allTasks.length ? allTasks : null);

            if (data.active_workforce !== undefined) {
                document.getElementById('statTeamTotal').textContent = data.active_workforce;
            }
            document.getElementById('statDeptsTotal').textContent = (data.tasks_by_department || []).length;
        } catch (err) {
            console.error('Error fetching dashboard stats:', err);
            if (allTasks.length) {
                applyDashboardStats(null, allTasks);
            }
        }
    }

    async function fetchTasks() {
        try {
            const res = await apiFetch('/api/tasks');
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Unable to load tasks.');
            allTasks = Array.isArray(data) ? data : (data.tasks || data.data || []);
            applyDashboardStats(null, allTasks);
            renderTaskList(filterVisibleTasks());
        } catch (err) {
            document.getElementById('taskListContainer').innerHTML = `<p class="alert alert-danger">${escapeHtml(err.message)}</p>`;
        }
    }

    function renderTaskList(tasks) {
        const container = document.getElementById('taskListContainer');
        if (!tasks || tasks.length === 0) {
            container.innerHTML = `<p class="text-center text-muted my-4 py-4 bg-white rounded-4 border shadow-sm">No tasks found.</p>`;
            return;
        }

        container.innerHTML = tasks.map(t => {
            const statusText = t.status || 'Pending';
            const overdue = isTaskOverdue(t);

            const badgeClass = statusText === 'Completed'
                ? 'bg-success-subtle text-success-emphasis border border-success-subtle'
                : statusText === 'In Progress'
                ? 'bg-primary-subtle text-primary-emphasis border border-primary-subtle'
                : statusText === 'Approval for Completed'
                ? 'bg-info-subtle text-info-emphasis border border-info-subtle'
                : 'bg-warning-subtle text-warning-emphasis border border-warning-subtle';

            const stateClass = overdue
                ? 'is-overdue'
                : statusText === 'Completed'
                ? 'is-completed'
                : statusText === 'Approval for Completed'
                ? 'is-approval'
                : statusText === 'Pending'
                ? 'is-pending'
                : '';

            return `
                <button type="button" class="tms-dir-card ${stateClass} text-start w-100" onclick="openTaskDetails(${Number(t.id)})">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="min-w-0">
                            <div class="tms-dir-title text-truncate">${escapeHtml(t.title)}</div>
                            <p class="tms-dir-desc">${escapeHtml(t.description || 'No description provided.')}</p>
                        </div>
                        <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                            ${overdue ? `<span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle px-2.5 py-1 rounded-pill" style="font-size: 11px; font-weight: 600;">Overdue</span>` : ''}
                            <span class="badge ${badgeClass} px-3 py-1 rounded-pill" style="font-size: 11px; font-weight: 600;">${escapeHtml(statusText)}</span>
                        </div>
                    </div>
                    <div class="tms-dir-meta">
                        <span><i class="bi bi-person-fill me-1 text-dark"></i>${escapeHtml(t.assigned_employee || 'Unassigned')}</span>
                        <span class="${overdue ? 'text-danger fw-semibold' : ''}"><i class="bi bi-calendar-event me-1 ${overdue ? 'text-danger' : 'text-warning'}"></i>${escapeHtml(t.due_date || 'No deadline')}</span>
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
            if (!matchesQuery) return false;
            if (currentFilter === 'all') return true;
            if (currentFilter === 'Overdue') return isTaskOverdue(task);
            return task.status === currentFilter;
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

    function syncBroadcastTeamCount() {
        const activeCount = (Array.isArray(allEmployees) ? allEmployees : [])
            .filter(emp => emp.status !== 'inactive').length;
        const badge = document.getElementById('modalBroadcastTeamCount');
        if (badge) {
            badge.textContent = `${activeCount} Members`;
        }
    }

    function populateBroadcastTargetUsers() {
        const selectEl = document.getElementById('broadcastTarget');
        if (!selectEl) return;

        const previousValue = selectEl.value || 'all';
        const members = (Array.isArray(allEmployees) ? allEmployees : [])
            .filter(emp => emp.status !== 'inactive');

        const allOptionText = members.length
            ? `All My Team Members (${members.length})`
            : 'All My Team Members';

        const userOptionsHtml = members.map(emp => {
            const name = escapeHtml(emp.name || 'Member');
            const meta = [emp.designation, emp.department].filter(Boolean).map(escapeHtml).join(' • ');
            const label = meta ? `${name} (${meta})` : name;
            return `<option value="${Number(emp.id)}">${label}</option>`;
        }).join('');

        selectEl.innerHTML = `<option value="all">${allOptionText}</option>${userOptionsHtml}`;

        const hasPrevious = [...selectEl.options].some(opt => opt.value === previousValue);
        selectEl.value = hasPrevious ? previousValue : 'all';
    }

    async function fetchEmployees() {
        try {
            const res = await apiFetch('/api/employees');
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Unable to load team members.');
            allEmployees = Array.isArray(data) ? data : (data.employees || []);
            document.getElementById('statTeamTotal').textContent = allEmployees.length;
            syncBroadcastTeamCount();
            populateBroadcastTargetUsers();
            renderEmployeeList(allEmployees);
        } catch (err) {
            document.getElementById('employeeListContainer').innerHTML = `<p class="alert alert-danger">${escapeHtml(err.message)}</p>`;
        }
    }

    function renderEmployeeList(employees) {
        const container = document.getElementById('employeeListContainer');
        if (!employees || employees.length === 0) {
            container.innerHTML = `<p class="text-center text-muted my-4 py-4 bg-white rounded-4 border shadow-sm">No team members found.</p>`;
            return;
        }

        container.innerHTML = employees.map(emp => {
            const isInactive = emp.status === 'inactive';
            const statusLabel = emp.status || 'active';

            return `
                <div class="card team-member-card ${isInactive ? 'is-inactive' : ''} d-flex align-items-center justify-content-between flex-row gap-3">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <div class="team-avatar-badge">${escapeHtml((emp.name || 'E').charAt(0).toUpperCase())}</div>
                        <div class="min-w-0">
                            <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 14.5px;">${escapeHtml(emp.name)}</h6>
                            <div class="team-meta-strip">
                                <span><i class="bi bi-briefcase me-1 text-secondary"></i>${escapeHtml(emp.designation || 'Employee')}</span>
                                <span><i class="bi bi-building me-1 text-secondary"></i>${escapeHtml(emp.department || 'General')}</span>
                                ${emp.email ? `<span class="text-truncate"><i class="bi bi-envelope me-1 text-secondary"></i>${escapeHtml(emp.email)}</span>` : ''}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        <span class="badge ${isInactive ? 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle' : 'bg-success-subtle text-success-emphasis border border-success-subtle'} rounded-pill px-3 py-1.5" style="font-size: 11px; font-weight: 600;">${escapeHtml(statusLabel)}</span>
                        <button class="btn btn-sm btn-outline-secondary team-icon-btn" type="button" title="${isInactive ? 'Activate' : 'Deactivate'} account"
                            onclick="toggleEmployeeStatus(${Number(emp.id)}, '${isInactive ? 'active' : 'inactive'}')">
                            <i class="bi ${isInactive ? 'bi-person-check' : 'bi-person-dash'}"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger team-icon-btn" type="button" title="Remove employee"
                            onclick="removeEmployee(${Number(emp.id)})"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            `;
        }).join('');
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

    function openBroadcastModal() {
        syncBroadcastTeamCount();
        populateBroadcastTargetUsers();
        if (!webBroadcastModal) {
            webBroadcastModal = new bootstrap.Modal(document.getElementById('modalBroadcast'));
        }
        webBroadcastModal.show();
    }

    document.getElementById('broadcastForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const headingEl = document.getElementById('broadcastHeading');
        const targetEl = document.getElementById('broadcastTarget');
        const contentEl = document.getElementById('broadcastContent');
        const submitBtn = document.getElementById('broadcastSubmitBtn');

        const title = headingEl.value.trim();
        const message = contentEl.value.trim();
        const selectedTarget = targetEl?.value || 'all';
        const isSpecificUser = selectedTarget !== 'all';

        const payload = {
            title,
            audience: isSpecificUser ? 'specific_user' : 'all',
            target_user_id: isSpecificUser ? Number(selectedTarget) : null,
            message
        };

        if (!title || !message) return;

        submitBtn.disabled = true;
        try {
            const res = await apiFetch('/api/admin/team/broadcast', {
                method: 'POST',
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Unable to publish notice.');

            headingEl.value = '';
            contentEl.value = '';
            targetEl.value = 'all';
            webBroadcastModal?.hide();
            window.alert(data.message || 'Notice published.');
        } catch (err) {
            window.alert(err.message);
        } finally {
            submitBtn.disabled = false;
        }
    });

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
        if (!window.confirm('Deactivate this employee account? Existing tasks and messages will be preserved.')) return;
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