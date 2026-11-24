<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECL TMS - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    :root {
        --ecl-dark: #12181f;
        --ecl-gold: #e5b72e;
    }

    body {
        background-color: transparent !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    /* Fixed Background Video Styling */
    .bg-video-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        z-index: -999;
        background-color: #12181f;
    }

    .bg-video {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        transform: translate(-50%, -50%);
        object-fit: cover;
        filter: brightness(0.45) contrast(1.05);
    }

    /* Transparent & Frosted Glass Top Navbar */
    .top-navbar {
        background: rgba(18, 24, 31, 0.35) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        height: 72px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        padding: 0 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .brand-logo-badge {
        background: linear-gradient(135deg, #e5b72e 0%, #b88a14 100%);
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #12181f;
        font-size: 20px;
        box-shadow: 0 0 15px rgba(229, 183, 46, 0.3);
    }

    /* Toggle Button Area */
    .sidebar-toggle-btn {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.14);
        color: #f8fafc;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
        margin-bottom: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.16);
    }

    .sidebar-toggle-btn:hover {
        background: rgba(229, 183, 46, 0.22);
        border-color: rgba(229, 183, 46, 0.5);
        color: #e5b72e;
        transform: translateY(-1px);
    }

    .sidebar-toggle-btn:active {
        transform: scale(0.96);
    }

    /* Transparent Menu Links */
    .sidebar-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        color: rgba(255, 255, 255, 0.84);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        border-radius: 13px;
        margin-bottom: 7px;
        overflow: hidden;
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
        background: rgba(255, 255, 255, 0.035);
        border: 1px solid rgba(255, 255, 255, 0.08);
        white-space: nowrap;
    }

    .sidebar-link i {
        color: rgba(255, 255, 255, 0.72);
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.11);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.22);
        transform: translateX(2px);
    }

    .sidebar-link:hover i {
        color: #ffe89e;
        transform: scale(1.05);
    }

    .sidebar-link.active {
        background: linear-gradient(90deg, rgba(229, 183, 46, 0.25) 0%, rgba(229, 183, 46, 0.09) 100%) !important;
        color: #ffe89e !important;
        border: 1px solid rgba(229, 183, 46, 0.5) !important;
        font-weight: 600;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.24);
    }

    .sidebar-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 7px;
        bottom: 7px;
        width: 3.5px;
        border-radius: 0 4px 4px 0;
        background: #e5b72e;
    }

    .sidebar-link.active i {
        color: #e5b72e !important;
    }

    body.sidebar-collapsed .sidebar-link:hover {
        transform: none;
    }

    body.sidebar-collapsed .sidebar-link.active::before {
        top: 6px;
        bottom: 6px;
        width: 3px;
    }

    /* Transparent User Profile Footer Box */
    .sidebar-footer-box {
        background: rgba(18, 24, 31, 0.5) !important;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #fff;
        white-space: nowrap;
        backdrop-filter: blur(10px);
    }

    /* Ultra-Transparent Dynamic Hover Card */
    .android-download-card {
        background: rgba(25, 135, 84, 0.18) !important;
        backdrop-filter: blur(14px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(14px) saturate(180%) !important;
        border-radius: 12px !important;
        border: 1px solid rgba(25, 135, 84, 0.35) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        padding: 9px 12px !important;
    }

    .android-download-card:hover {
        transform: translateY(-3px) scale(1.02);
        background: rgba(25, 135, 84, 0.3) !important;
        border-color: rgba(40, 167, 69, 0.8) !important;
        box-shadow: 0 10px 25px rgba(25, 135, 84, 0.3) !important;
    }

    .android-download-card:hover .download-icon-box {
        transform: scale(1.1) rotate(6deg);
        background: #198754 !important;
        color: #ffffff !important;
        box-shadow: 0 0 12px rgba(25, 135, 84, 0.6);
    }

    .download-icon-box {
        transition: all 0.25s ease;
    }

    /* Transparent Cyber Security Text */
    .cyber-security-text {
        color: rgba(255, 255, 255, 0.6) !important;
        font-size: 8.5px;
        letter-spacing: 0.3px;
    }

    /* COLLAPSED STATE STYLING */
    body.sidebar-collapsed .frosted-sidebar {
        width: 76px !important;
        padding: 16px 8px;
    }

    body.sidebar-collapsed .sidebar-link span,
    body.sidebar-collapsed .sidebar-footer-box .user-info-text,
    body.sidebar-collapsed .android-download-card,
    body.sidebar-collapsed .cyber-security-text {
        display: none !important;
    }

    body.sidebar-collapsed .sidebar-link {
        justify-content: center;
        padding: 11px 0;
    }

    body.sidebar-collapsed .sidebar-footer-box {
        justify-content: center;
        padding: 10px 0;
    }

    .flex-grow-1 {
        min-width: 0 !important;
    }

    .sidebar-footer-box .text-muted {
        color: rgba(255, 255, 255, 0.68) !important;
    }

    /* Contextual Back Action inside Main Content Area */
    .tms-context-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(18, 24, 31, 0.65);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        color: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }

    .tms-context-back-btn:hover {
        background: rgba(229, 183, 46, 0.2);
        border-color: rgba(229, 183, 46, 0.5);
        color: #ffe89e;
        transform: translateX(-2px);
    }

    /* --- Notifications Read / Unread Styling --- */
    #webNotificationsModal .modal-content {
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.28);
        background: #ffffff;
    }

    .web-notif-top-bar {
        height: 4px;
        width: 100%;
        background: linear-gradient(90deg, #e5b72e 0%, #075e54 55%, #128c7e 100%);
    }

    #webNotificationsModal .modal-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 18px;
    }

    .web-notif-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 11px;
        background: #fef3c7;
        color: #b45309;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    #webNotificationsModal .modal-body {
        background: #f8fafc;
        padding: 14px 16px;
        max-height: 410px;
        overflow-y: auto;
    }

    .web-notif-card {
        width: 100%;
        text-align: left;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #cbd5e1;
        border-radius: 14px;
        padding: 12px 14px;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
        transition: transform 0.16s ease, box-shadow 0.16s ease, border-color 0.16s ease;
    }

    .web-notif-card + .web-notif-card {
        margin-top: 10px;
    }

    .web-notif-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.07);
    }

    .web-notif-card.is-unread {
        background: #fffbeb;
        border-color: #fde68a;
        border-left: 4px solid #d97706;
    }

    .web-notif-card.is-read {
        background: #ffffff;
        opacity: 0.82;
    }

    .web-unread-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #d97706;
        display: inline-block;
        flex-shrink: 0;
    }

    .web-notif-time {
        font-size: 11px;
        color: #64748b;
    }

    /* --- Polished Task Modal, UX Metadata & Task Discussion UI --- */
    #webTaskModal .modal-content {
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 26px 64px rgba(15, 23, 42, 0.32);
        background: #ffffff;
    }

    .tms-modal-top-bar {
        height: 4px;
        width: 100%;
        background: linear-gradient(90deg, #075e54 0%, #128c7e 55%, #e5b72e 100%);
    }

    #webTaskModal .modal-header {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 22px;
    }

    #webTaskModal .modal-body {
        padding: 18px 22px 22px;
        background: #ffffff;
    }

    .tms-ux-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    @media (min-width: 768px) {
        .tms-ux-meta-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    .tms-ux-meta-cell {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 4px;
        min-width: 0;
    }

    .tms-ux-meta-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
    }

    .tms-ux-meta-value {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .tms-ux-detail-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #075e54;
        border-radius: 14px;
        padding: 12px 15px;
    }

    .tms-ux-detail-label {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        margin-bottom: 5px;
        display: block;
    }

    #webTaskDescription.tms-ux-detail-text {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        font-size: 13.5px;
        line-height: 1.5;
        color: #1e293b;
        white-space: pre-line;
    }

    .tms-ux-status-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 11px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
    }

    .wa-panel-box {
        background-color: #efeae2;
        background-image: radial-gradient(rgba(17, 27, 33, 0.055) 1px, transparent 0);
        background-size: 18px 18px;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 12px;
        box-shadow: inset 0 1px 3px rgba(15, 23, 42, 0.06);
    }

    .wa-panel-header {
        background: linear-gradient(135deg, #075e54 0%, #128c7e 100%);
        color: #ffffff;
        border-radius: 12px;
        padding: 9px 14px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13.5px;
        font-weight: 600;
        box-shadow: 0 3px 10px rgba(7, 94, 84, 0.22);
    }

    #webTaskMessages.wa-messages-area {
        height: 265px !important;
        overflow-y: auto !important;
        border: none !important;
        border-radius: 14px !important;
        padding: 10px !important;
        margin-bottom: 12px !important;
        background: rgba(255, 255, 255, 0.42) !important;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .wa-msg-line {
        display: flex;
        flex-direction: column;
        max-width: 78%;
    }

    .wa-msg-line.is-mine {
        align-self: flex-end;
        align-items: flex-end;
    }

    .wa-msg-line.is-other {
        align-self: flex-start;
        align-items: flex-start;
    }

    .wa-msg-bubble {
        padding: 8px 13px;
        border-radius: 13px;
        font-size: 13.5px;
        line-height: 1.45;
        color: #111b21;
        box-shadow: 0 1px 2px rgba(11, 20, 26, 0.14);
        word-break: break-word;
    }

    .wa-msg-line.is-mine .wa-msg-bubble {
        background: #d9fdd3;
        border-top-right-radius: 3px;
    }

    .wa-msg-line.is-other .wa-msg-bubble {
        background: #ffffff;
        border-top-left-radius: 3px;
    }

    .wa-msg-author {
        font-size: 11.5px;
        font-weight: 700;
        color: #075e54;
        margin-bottom: 2px;
    }

    .wa-msg-stamp {
        font-size: 11px;
        color: #667781;
        margin-top: 3px;
        padding: 0 3px;
    }

    #webTaskMessageInput.wa-input-pill {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 999px !important;
        padding: 10px 16px !important;
        font-size: 13.5px;
        color: #111b21 !important;
        box-shadow: 0 1px 3px rgba(11, 20, 26, 0.08) !important;
    }

    #webTaskMessageInput.wa-input-pill:focus {
        border-color: #128c7e !important;
        box-shadow: 0 0 0 2px rgba(18, 140, 126, 0.18) !important;
    }

    .wa-send-btn {
        width: 42px;
        height: 42px;
        border-radius: 50% !important;
        background: #008069 !important;
        border: none !important;
        color: #ffffff !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 128, 105, 0.35);
    }

    .wa-send-btn:hover {
        background: #017561 !important;
    }

    @media (max-width: 767.98px) {
        .top-navbar {
            padding: 0 14px;
            height: 64px;
        }

        #sec-tasks .card.d-flex.flex-row {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px;
        }
    }
    </style>
</head>
<body>

    <!-- Background Video Container -->
    <div class="bg-video-container">
        <video autoplay muted loop playsinline class="bg-video">
            <source src="{{ asset('video/TMS_video.mp4') }}" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>
    </div>

    <nav class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <!-- Actual Logo Image from public/images/logo.png -->
            <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="height: 38px;">
                <img src="{{ asset('images/logo.png') }}" alt="ECL Logo" style="height: 36px; width: auto; object-fit: contain;">
            </div>

            <!-- Vertical Divider Line -->
            <div style="width: 1px; height: 26px; background: rgba(255, 255, 255, 0.2);"></div>

            <!-- Task Management System Titles -->
            <div class="d-flex flex-column">
                <span class="fw-bold text-warning" style="font-size: 13px; letter-spacing: 0.2px; line-height: 1.2;">Task Management</span>
                <span class="fw-bold text-white" style="font-size: 13px; letter-spacing: 0.3px; line-height: 1.2;">System</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button onclick="openNotificationsModal()" class="btn border-0 bg-white bg-opacity-10 text-white rounded-circle p-2.5 position-relative shadow-sm" style="width: 40px; height: 40px;" aria-label="Notifications">
                <i class="bi bi-bell-fill text-warning"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifBadge">0</span>
            </button>
            <button onclick="logoutUser()" class="btn btn-outline-light btn-sm border-0 bg-white bg-opacity-10 text-white px-3.5 py-2 rounded-pill fw-semibold shadow-sm">
                <i class="bi bi-box-arrow-right me-1.5"></i> Logout
            </button>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container-fluid p-0">
        <div class="d-flex">

            <!-- Collapsible Sidebar -->
            <div class="sidebar-wrapper d-none d-lg-block" id="sidebarWrapper">
                <div class="frosted-sidebar">
                    <div>
                        <!-- Toggle Hamburger Button -->
                        <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                            <div class="sidebar-toggle-btn mx-auto" onclick="toggleSidebar()" title="Toggle Sidebar">
                                <i class="bi bi-list fs-4"></i>
                            </div>
                        </div>

                        <!-- Navigation Links -->
                        <div class="d-flex flex-column">
                            <a href="#" onclick="switchTab('overview'); return false;" id="nav-overview" class="sidebar-link active" title="Overview">
                                <i class="bi bi-grid-1x2-fill fs-5"></i>
                                <span>Overview</span>
                            </a>

                            <a href="#" onclick="switchTab('tasks'); return false;" id="nav-tasks" class="sidebar-link" title="Tasks">
                                <i class="bi bi-list-task fs-5"></i>
                                <span>Tasks</span>
                            </a>

                            <a href="#" onclick="switchTab('team'); return false;" id="nav-team" class="sidebar-link" title="Team">
                                <i class="bi bi-people-fill fs-5"></i>
                                <span>Team</span>
                            </a>
                        </div>
                    </div>

                    <!-- Bottom Section: Properly Adjusted & Clean Download Card -->
                    <div class="d-flex flex-column gap-2">
                        <div class="android-download-wrapper px-1">
                            <a href="/downloads/ecl-tms.apk" download class="card text-decoration-none p-2.5 android-download-card d-block">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden flex-grow-1 me-2">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 download-icon-box" style="background: rgba(25, 135, 84, 0.25); color: #2ecc71; width: 34px; height: 34px; font-size: 18px;">
                                            <i class="bi bi-android2"></i>
                                        </div>
                                        <div class="overflow-hidden w-100">
                                            <h6 class="fw-bold mb-0 text-white text-truncate" style="font-size: 12.5px; line-height: 1.2;">Download App</h6>
                                            <span class="d-block text-white-50 text-truncate" style="font-size: 9.5px; line-height: 1.2;">ECL Mobile Client</span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 text-success fs-5 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                        <i class="bi bi-download"></i>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- User Profile Footer Box -->
                        <div class="sidebar-footer-box mt-1">
                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                <div class="rounded-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 30px; height: 30px; font-size: 12px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="user-info-text overflow-hidden">
                                    <span class="fw-bold d-block text-truncate" style="font-size: 11.5px;" id="sidebarUserName">Admin User</span>
                                    <span class="text-muted d-block text-truncate" style="font-size: 9px;">Active Session</span>
                                </div>
                            </div>
                            <i class="bi bi-box-arrow-right fs-5 text-warning ms-2" style="cursor: pointer;" onclick="logoutUser()" title="Logout"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-grow-1 p-4" style="min-height: calc(100vh - 72px); overflow-y: auto;">
                <div class="mx-auto" style="max-width: 1120px;">
                    <div id="tmsContextBackBar" class="mb-3 d-none">
                        <button type="button" onclick="goBack()" class="tms-context-back-btn" aria-label="Go back">
                            <i class="bi bi-arrow-left"></i>
                            <span>Back</span>
                        </button>
                    </div>
                    @yield('content')
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="webNotificationsModal" tabindex="-1" aria-labelledby="webNotificationsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="web-notif-top-bar"></div>
                <div class="modal-header d-flex justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                        <span class="web-notif-icon-box">
                            <i class="bi bi-bell-fill"></i>
                        </span>
                        <div class="min-w-0">
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="modal-title fw-bold text-dark m-0" id="webNotificationsTitle">Notifications</h6>
                                <span class="badge rounded-pill bg-warning text-dark" id="webNotifUnreadPill" style="font-size: 10.5px;">0 Unread</span>
                            </div>
                            <small class="text-muted d-block text-truncate" style="font-size: 11.5px;">Click a notification to mark as read &amp; open task</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        <button type="button" class="btn btn-white btn-sm border rounded-pill px-2.5 py-1 fw-semibold text-dark" style="font-size: 11.5px;" onclick="markAllWebNotificationsRead()">
                            Mark all read
                        </button>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body" id="webNotificationsList">
                    <p class="text-muted text-center my-3">Loading notifications...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="webTaskModal" tabindex="-1" aria-labelledby="webTaskTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="tms-modal-top-bar"></div>
                <div class="modal-header d-flex justify-content-between align-items-start gap-3">
                    <div class="min-w-0">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h5 class="modal-title fw-bold text-dark m-0" id="webTaskTitle">Task details</h5>
                            <span class="badge rounded-pill text-bg-warning px-2.5 py-1" id="webTaskStatusBadge"></span>
                        </div>
                        <small class="text-muted d-block text-truncate" id="webTaskMeta"></small>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body vstack gap-3">
                    <!-- 4-Column Structured Task Metadata -->
                    <div class="tms-ux-meta-grid">
                        <div class="tms-ux-meta-cell">
                            <span class="tms-ux-meta-label"><i class="bi bi-flag-fill me-1 text-warning"></i>Priority</span>
                            <span class="tms-ux-meta-value" id="webTaskPriority">--</span>
                        </div>
                        <div class="tms-ux-meta-cell">
                            <span class="tms-ux-meta-label"><i class="bi bi-person-check-fill me-1 text-primary"></i>Assigned To</span>
                            <span class="tms-ux-meta-value" id="webTaskAssignee">--</span>
                        </div>
                        <div class="tms-ux-meta-cell">
                            <span class="tms-ux-meta-label"><i class="bi bi-calendar-event-fill me-1 text-danger"></i>Deadline</span>
                            <span class="tms-ux-meta-value text-danger" id="webTaskDueDate">--</span>
                        </div>
                        <div class="tms-ux-meta-cell">
                            <span class="tms-ux-meta-label"><i class="bi bi-layers-fill me-1 text-success"></i>Task Status</span>
                            <span class="tms-ux-meta-value" id="webTaskStatusText">--</span>
                        </div>
                    </div>

                    <!-- Task Detail / Description -->
                    <div class="tms-ux-detail-box">
                        <span class="tms-ux-detail-label"><i class="bi bi-file-earmark-text me-1"></i>Task Detail</span>
                        <p class="tms-ux-detail-text" id="webTaskDescription"></p>
                    </div>

                    <!-- Update Status Bar -->
                    <div class="tms-ux-status-bar">
                        <label for="webTaskStatusSelect" class="form-label fw-semibold text-dark mb-0 small">
                            <i class="bi bi-arrow-repeat me-1 text-primary"></i>Update status
                        </label>
                        <div class="d-flex align-items-center gap-2 ms-sm-auto">
                            <select id="webTaskStatusSelect" class="form-select form-select-sm bg-white rounded-3" style="min-width: 195px;">
                                <option>Pending</option>
                                <option>In Progress</option>
                                <option>Approval for Completed</option>
                                <option>Completed</option>
                            </select>
                            <button class="btn btn-sm btn-dark rounded-3 px-3 fw-semibold" id="webTaskStatusSave" type="button">Save</button>
                        </div>
                    </div>

                    <!-- Task Discussion -->
                    <div class="wa-panel-box">
                        <div class="wa-panel-header">
                            <span><i class="bi bi-chat-dots-fill me-1.5"></i> Task discussion</span>
                        </div>
                        <div id="webTaskMessages" class="wa-messages-area"></div>
                        <form id="webTaskMessageForm" class="d-flex align-items-center gap-2">
                            <input id="webTaskMessageInput" class="form-control wa-input-pill" maxlength="5000" placeholder="Write a message..." autocomplete="off" required>
                            <button class="btn wa-send-btn" type="submit" aria-label="Send message"><i class="bi bi-send-fill small"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.api-config')
    <script>
        let currentWebTaskId = null;
        let webTaskRefreshTimer = null;
        let webTaskModal;
        let webNotificationsModal;
        let webNotificationsCache = [];

        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
        }

        function getSafePreviousPath() {
            const currentPath = window.location.pathname;
            const previousUrl = document.referrer ? new URL(document.referrer, window.location.origin) : null;
            const previousPath = previousUrl && previousUrl.origin === window.location.origin ? previousUrl.pathname : '';
            const safePreviousPaths = ['/admin/dashboard', '/admin/assign-task', '/employee/dashboard'];
            return (safePreviousPaths.includes(previousPath) && previousPath !== currentPath) ? previousPath : null;
        }

        function syncContextBackButton() {
            const bar = document.getElementById('tmsContextBackBar');
            if (!bar) return;

            const currentPath = window.location.pathname;
            const activeNonOverviewTab = currentPath === '/admin/dashboard' &&
                (document.getElementById('nav-tasks')?.classList.contains('active') ||
                 document.getElementById('nav-team')?.classList.contains('active'));

            const hasSafeReferer = Boolean(getSafePreviousPath());
            const shouldShow = Boolean(activeNonOverviewTab || hasSafeReferer || currentPath === '/admin/assign-task');
            bar.classList.toggle('d-none', !shouldShow);
        }

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content-sec').forEach(section => section.classList.add('d-none'));
            document.getElementById(`sec-${tabName}`)?.classList.remove('d-none');
            document.querySelectorAll('.sidebar-link').forEach(link => link.classList.toggle('active', link.id === `nav-${tabName}`));
            document.querySelectorAll('.tms-nav-btn').forEach(btn => btn.classList.toggle('active', btn.id === `btn-sec-${tabName}`));
            syncContextBackButton();
        }

        function logoutUser() {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user_info');
            window.location.replace('/login');
        }

        function goBack() {
            const currentPath = window.location.pathname;
            if (currentPath === '/admin/dashboard' &&
                (document.getElementById('nav-tasks')?.classList.contains('active') ||
                 document.getElementById('nav-team')?.classList.contains('active'))) {
                switchTab('overview');
                return;
            }

            const safePreviousPath = getSafePreviousPath();
            if (safePreviousPath) {
                window.history.back();
                return;
            }

            const fallbackPath = currentPath.startsWith('/employee/') ? '/employee/dashboard'
                : currentPath === '/admin/assign-task' ? '/admin/dashboard'
                : null;

            if (fallbackPath) {
                window.location.assign(fallbackPath);
            }
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, character => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            })[character]);
        }

        function formatReadableTimestamp(val) {
            if (!val) return '';
            const dt = new Date(val);
            if (Number.isNaN(dt.getTime())) return String(val);
            return dt.toLocaleString('en-IN', {
                day: '2-digit',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getWebAuthHeaders(extraHeaders = {}) {
            return {
                'Authorization': `Bearer ${localStorage.getItem('auth_token') || ''}`,
                'Accept': 'application/json',
                ...extraHeaders
            };
        }

        async function apiFetch(url, options = {}) {
            const headers = getWebAuthHeaders(options.headers || {});
            if (options.body && !headers['Content-Type'] && !(options.body instanceof FormData)) {
                headers['Content-Type'] = 'application/json';
            }
            const response = await fetch(window.eclApiUrl(url), { ...options, headers });
            if (response.status === 401) {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_info');
                window.location.replace('/login');
            }
            return response;
        }

        function getTaskStatusClass(status) {
            switch (status) {
                case 'Completed': return 'text-bg-success';
                case 'In Progress': return 'text-bg-primary';
                case 'Approval for Completed': return 'text-bg-info';
                default: return 'text-bg-warning';
            }
        }

        async function openTaskDetails(taskId) {
            currentWebTaskId = taskId;
            if (!webTaskModal) webTaskModal = new bootstrap.Modal(document.getElementById('webTaskModal'));
            document.getElementById('webTaskTitle').textContent = 'Loading task...';
            document.getElementById('webTaskMessages').innerHTML = '<p class="text-muted text-center my-auto">Loading discussion...</p>';
            webTaskModal.show();
            try {
                const response = await apiFetch(`/api/tasks/${taskId}`);
                const task = await response.json();
                if (!response.ok) throw new Error(task.message || 'Unable to load task.');
                renderWebTask(task);
                await loadWebTaskMessages(taskId);
                if (webTaskRefreshTimer) clearInterval(webTaskRefreshTimer);
                webTaskRefreshTimer = setInterval(() => loadWebTaskMessages(taskId), 5000);
            } catch (error) {
                document.getElementById('webTaskTitle').textContent = 'Task unavailable';
                document.getElementById('webTaskMessages').innerHTML = `<p class="text-danger my-auto">${escapeHtml(error.message)}</p>`;
            }
        }

        function renderWebTask(task) {
            const viewRole = window.eclViewRole || 'admin';
            document.getElementById('webTaskTitle').textContent = task.title || 'Task';
            document.getElementById('webTaskMeta').textContent = task.department || 'No department';
            const badge = document.getElementById('webTaskStatusBadge');
            badge.textContent = task.status || 'Pending';
            badge.className = `badge rounded-pill px-2.5 py-1 ${getTaskStatusClass(task.status)}`;
            document.getElementById('webTaskPriority').textContent = `${task.priority || 'Medium'} priority`;
            document.getElementById('webTaskAssignee').textContent = task.assigned_employee || 'Unassigned';
            document.getElementById('webTaskDueDate').textContent = task.due_date || 'No due date';
            const statusTextEl = document.getElementById('webTaskStatusText');
            if (statusTextEl) statusTextEl.textContent = task.status || 'Pending';
            document.getElementById('webTaskDescription').textContent = task.description || 'No description provided.';
            const statusSelect = document.getElementById('webTaskStatusSelect');
            statusSelect.value = task.status || 'Pending';
            [...statusSelect.options].forEach(option => {
                option.hidden = viewRole === 'employee' && option.value === 'Completed';
            });
        }

        async function loadWebTaskMessages(taskId) {
            if (String(currentWebTaskId) !== String(taskId)) return;
            try {
                const response = await apiFetch(`/api/tasks/${taskId}/messages`);
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Unable to load discussion.');
                const messages = Array.isArray(result) ? result : (result.data || []);
                const container = document.getElementById('webTaskMessages');
                if (!messages.length) {
                    container.innerHTML = '<p class="text-muted text-center my-auto">No messages yet. Start the task discussion.</p>';
                    return;
                }
                const currentUser = JSON.parse(localStorage.getItem('user_info') || '{}');
                container.innerHTML = messages.map(message => {
                    const mine = Number(message.user_id) === Number(currentUser.id);
                    const timeLabel = formatReadableTimestamp(message.created_at);
                    return `<div class="wa-msg-line ${mine ? 'is-mine' : 'is-other'}">
                        <div class="wa-msg-bubble">
                            ${!mine ? `<div class="wa-msg-author">${escapeHtml(message.user?.name || 'User')}</div>` : ''}
                            <div>${escapeHtml(message.message || '')}</div>
                        </div>
                        ${timeLabel ? `<span class="wa-msg-stamp">${escapeHtml(timeLabel)}</span>` : ''}
                    </div>`;
                }).join('');
                container.scrollTop = container.scrollHeight;
            } catch (error) {
                document.getElementById('webTaskMessages').innerHTML = `<p class="text-danger my-auto">${escapeHtml(error.message)}</p>`;
            }
        }

        function syncNavbarNotifBadge(unreadCount) {
            const badge = document.getElementById('notifBadge');
            if (badge) {
                badge.textContent = unreadCount;
                badge.classList.toggle('d-none', unreadCount === 0);
            }

            const pill = document.getElementById('webNotifUnreadPill');
            if (pill) {
                pill.textContent = `${unreadCount} Unread`;
                pill.className = unreadCount > 0
                    ? 'badge rounded-pill bg-warning text-dark'
                    : 'badge rounded-pill bg-light text-muted border';
            }
        }

        function renderWebNotificationsList(notifications) {
            webNotificationsCache = Array.isArray(notifications) ? notifications : [];
            const unread = webNotificationsCache.filter(notification => !notification.is_read).length;
            syncNavbarNotifBadge(unread);

            const container = document.getElementById('webNotificationsList');
            if (!container) return;

            if (!webNotificationsCache.length) {
                container.innerHTML = '<p class="text-muted text-center my-3 mb-0">You have no notifications.</p>';
                return;
            }

            container.innerHTML = webNotificationsCache.map(notification => {
                const isUnread = !notification.is_read;
                const timeText = formatReadableTimestamp(notification.created_at);
                const statusBadge = isUnread
                    ? '<span class="badge rounded-pill bg-warning text-dark px-2 py-0.5" style="font-size: 10px;">Unread</span>'
                    : '<span class="badge rounded-pill bg-light text-muted border px-2 py-0.5" style="font-size: 10px;">Read</span>';

                return `<button type="button" class="web-notif-card ${isUnread ? 'is-unread' : 'is-read'}"
                    onclick="openNotificationTask(${Number(notification.id)}, ${Number(notification.task_id || 0)})">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <div class="d-flex align-items-center gap-1.5 min-w-0">
                            ${isUnread ? '<span class="web-unread-dot"></span>' : ''}
                            <span class="fw-semibold text-dark small text-truncate">${escapeHtml(notification.title)}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                            ${statusBadge}
                            <span class="web-notif-time">${escapeHtml(timeText)}</span>
                        </div>
                    </div>
                    <span class="small text-muted d-block">${escapeHtml(notification.message)}</span>
                </button>`;
            }).join('');
        }

        async function markAllWebNotificationsRead() {
            const unreadItems = webNotificationsCache.filter(n => !n.is_read && n.id);
            if (!unreadItems.length) return;

            webNotificationsCache = webNotificationsCache.map(n => ({ ...n, is_read: true }));
            renderWebNotificationsList(webNotificationsCache);

            await Promise.allSettled(
                unreadItems.map(n => apiFetch(`/api/notifications/${n.id}/read`, { method: 'POST' }))
            );
        }

        async function openNotificationsModal() {
            if (!webNotificationsModal) webNotificationsModal = new bootstrap.Modal(document.getElementById('webNotificationsModal'));
            webNotificationsModal.show();
            const container = document.getElementById('webNotificationsList');
            container.innerHTML = '<p class="text-muted text-center my-3">Loading notifications...</p>';
            try {
                const response = await apiFetch('/api/notifications');
                const notifications = await response.json();
                if (!response.ok) throw new Error(notifications.message || 'Unable to load notifications.');
                renderWebNotificationsList(notifications);
            } catch (error) {
                container.innerHTML = `<p class="text-danger text-center my-3 mb-0">${escapeHtml(error.message)}</p>`;
            }
        }

        async function openNotificationTask(notificationId, taskId) {
            try {
                if (notificationId) {
                    const response = await apiFetch(`/api/notifications/${notificationId}/read`, { method: 'POST' });
                    if (!response.ok) throw new Error('Unable to mark notification as read.');
                    webNotificationsCache = webNotificationsCache.map(n =>
                        Number(n.id) === Number(notificationId) ? { ...n, is_read: true } : n
                    );
                    const unreadCount = webNotificationsCache.filter(n => !n.is_read).length;
                    syncNavbarNotifBadge(unreadCount);
                }
                webNotificationsModal?.hide();
                if (taskId) openTaskDetails(taskId);
            } catch (error) {
                window.alert(error.message);
            }
        }

        document.addEventListener('DOMContentLoaded', async () => {
            syncContextBackButton();
            const userInfo = JSON.parse(localStorage.getItem('user_info') || '{}');
            if (userInfo.name) document.getElementById('sidebarUserName').textContent = userInfo.name;
            if (localStorage.getItem('auth_token')) {
                try {
                    const response = await apiFetch('/api/notifications');
                    if (response.ok) {
                        const notifications = await response.json();
                        const unread = notifications.filter(notification => !notification.is_read).length;
                        syncNavbarNotifBadge(unread);
                    }
                } catch (error) {
                    console.error('Unable to load notification count:', error);
                }
            }
            document.getElementById('webTaskModal').addEventListener('hidden.bs.modal', () => {
                currentWebTaskId = null;
                if (webTaskRefreshTimer) clearInterval(webTaskRefreshTimer);
            });
            document.getElementById('webTaskStatusSave').addEventListener('click', async () => {
                if (!currentWebTaskId) return;
                const status = document.getElementById('webTaskStatusSelect').value;
                const endpoint = window.eclViewRole === 'employee'
                    ? `/api/employee/tasks/${currentWebTaskId}`
                    : `/api/admin/tasks/${currentWebTaskId}`;
                const button = document.getElementById('webTaskStatusSave');
                button.disabled = true;
                try {
                    const response = await apiFetch(endpoint, { method: 'PUT', body: JSON.stringify({ status }) });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'Unable to update task status.');
                    renderWebTask(result.task);
                    if (typeof fetchTasks === 'function') fetchTasks();
                } catch (error) {
                    window.alert(error.message);
                } finally {
                    button.disabled = false;
                }
            });
            document.getElementById('webTaskMessageForm').addEventListener('submit', async event => {
                event.preventDefault();
                if (!currentWebTaskId) return;
                const input = document.getElementById('webTaskMessageInput');
                const message = input.value.trim();
                if (!message) return;
                const button = event.currentTarget.querySelector('button');
                button.disabled = true;
                try {
                    const response = await apiFetch(`/api/tasks/${currentWebTaskId}/messages`, {
                        method: 'POST',
                        body: JSON.stringify({ message })
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'Unable to send message.');
                    input.value = '';
                    await loadWebTaskMessages(currentWebTaskId);
                } catch (error) {
                    window.alert(error.message);
                } finally {
                    button.disabled = false;
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            if (window.eclViewRole === 'employee') {
                document.querySelectorAll('#nav-team, #nav-overview').forEach(element => element.classList.add('d-none'));
                document.getElementById('nav-tasks')?.classList.add('active');
                syncContextBackButton();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>