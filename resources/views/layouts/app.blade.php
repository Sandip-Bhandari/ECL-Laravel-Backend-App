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

    /* Collapsible Frosted Glass Sidebar */
    .sidebar-wrapper {
        padding: 20px 14px;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .frosted-sidebar {
        width: 260px;
        background: rgba(18, 24, 31, 0.4) !important;
        backdrop-filter: blur(22px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(22px) saturate(160%) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        border-radius: 20px !important;
        min-height: calc(100vh - 96px);
        padding: 16px 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    /* Toggle Button Area */
    .sidebar-toggle-btn {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 15px;
    }

    .sidebar-toggle-btn:hover {
        background: rgba(229, 183, 46, 0.2);
        border-color: rgba(229, 183, 46, 0.4);
        color: #e5b72e;
    }

    /* Transparent Menu Links */
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        border-radius: 12px;
        margin-bottom: 6px;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        white-space: nowrap;
    }

    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.2);
    }

    .sidebar-link.active {
        background: rgba(229, 183, 46, 0.2) !important;
        color: #ffe89e !important;
        border: 1px solid rgba(229, 183, 46, 0.4);
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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
    <!-- Coal India Circular Monogram Logo Placeholder -->
    

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
</div>

        <div class="d-flex align-items-center gap-3">
            <button class="btn border-0 bg-white bg-opacity-10 text-white rounded-circle p-2.5 position-relative shadow-sm" style="width: 40px; height: 40px;">
                <i class="bi bi-bell-fill text-warning"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
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
                    @yield('content')
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
        }

        // Populate user name in sidebar footer if available
        document.addEventListener('DOMContentLoaded', () => {
            const userInfo = JSON.parse(localStorage.getItem('user_info') || '{}');
            if (userInfo && userInfo.name) {
                const el = document.getElementById('sidebarUserName');
                if (el) el.textContent = userInfo.name;
            }
        });
    </script>
    @stack('scripts')
</body>
</html>