<style>
    .tms-bottom-nav {
        position: fixed;
        bottom: 10px;
        left: 12px;
        right: 12px;
        background: rgba(18, 24, 31, 0.86);
        backdrop-filter: blur(18px) saturate(160%);
        -webkit-backdrop-filter: blur(18px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 20px;
        padding: 7px 10px calc(7px + env(safe-area-inset-bottom, 0px));
        display: flex;
        justify-content: space-around;
        align-items: center;
        gap: 6px;
        z-index: 1000;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.34);
    }

    .tms-nav-btn {
        flex: 1 1 0;
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.72);
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.2px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        padding: 4px 6px;
        border-radius: 14px;
        cursor: pointer;
        transition: color 0.2s ease, transform 0.15s ease;
    }

    .tms-nav-btn:hover {
        color: #ffffff;
    }

    .tms-nav-btn:active {
        transform: scale(0.96);
    }

    .tms-nav-icon {
        font-size: 17px;
        line-height: 1;
        padding: 5px 16px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.22s ease, color 0.22s ease, box-shadow 0.22s ease;
    }

    .tms-nav-btn.active {
        color: #ffe89e;
    }

    .tms-nav-btn.active .tms-nav-icon {
        background: rgba(229, 183, 46, 0.22);
        color: #e5b72e;
        box-shadow: 0 0 12px rgba(229, 183, 46, 0.22);
    }

    /* Desktop/large screens par sidebar already visible hai, isliye mobile/tablet ke liye neat padding */
    @media (max-width: 991.98px) {
        body {
            padding-bottom: 82px !important;
        }
    }
</style>

<nav class="tms-bottom-nav d-lg-none" aria-label="Bottom Navigation">
    <button type="button" class="tms-nav-btn active" id="btn-sec-overview" onclick="switchTab('overview')">
        <span class="tms-nav-icon"><i class="bi bi-grid-1x2-fill"></i></span>
        <span>Overview</span>
    </button>
    <button type="button" class="tms-nav-btn" id="btn-sec-tasks" onclick="switchTab('tasks')">
        <span class="tms-nav-icon"><i class="bi bi-list-task"></i></span>
        <span>Tasks</span>
    </button>
    <button type="button" class="tms-nav-btn" id="btn-sec-team" onclick="switchTab('team')">
        <span class="tms-nav-icon"><i class="bi bi-people-fill"></i></span>
        <span>Team</span>
    </button>
</nav>