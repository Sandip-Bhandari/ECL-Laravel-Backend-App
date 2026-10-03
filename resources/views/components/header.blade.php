<div class="app-header d-flex justify-content-between align-items-center mb-3">
    <div>
        <div class="header-title" id="appHeaderTitle">Task<br>Management</div>
        <div class="header-subtitle" id="userSubtitle">Loading user...</div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="position-relative" onclick="openNotificationsModal()" style="cursor: pointer;">
            <i class="bi bi-bell-fill fs-4 text-warning"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifBadge" style="font-size: 10px;">0</span>
        </span>
        <button onclick="logout()" class="btn btn-link text-white p-0 fs-4"><i class="bi bi-box-arrow-right"></i></button>
    </div>
</div>