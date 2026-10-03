<style>
    .bottom-nav {
        position: fixed; bottom: 0; left: 0; right: 0;
        background-color: #fff; border-top: 1px solid #EFEAE1;
        display: flex; justify-content: space-around; padding: 8px 0; z-index: 1000;
    }

    .nav-item-btn {
        background: none; border: none; color: var(--app-text-muted);
        font-size: 12px; font-weight: 600; display: flex;
        flex-direction: column; align-items: center; gap: 2px;
    }
    .nav-item-btn.active { color: var(--app-dark); }
    .nav-item-btn.active .nav-icon-wrapper {
        background-color: #F7F3EC; border-radius: 16px; padding: 4px 16px;
    }
    .nav-icon-wrapper { font-size: 18px; }
</style>

<div class="bottom-nav">
    <button class="nav-item-btn active" id="btn-sec-overview" onclick="switchTab('overview')">
        <div class="nav-icon-wrapper"><i class="bi bi-grid-fill"></i></div>
        Overview
    </button>
    <button class="nav-item-btn" id="btn-sec-tasks" onclick="switchTab('tasks')">
        <div class="nav-icon-wrapper"><i class="bi bi-file-earmark-text-fill"></i></div>
        Tasks
    </button>
    <button class="nav-item-btn" id="btn-sec-team" onclick="switchTab('team')">
        <div class="nav-icon-wrapper"><i class="bi bi-people-fill"></i></div>
        Team
    </button>
</div>