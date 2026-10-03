<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workforce & Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --app-dark: #3B2314;
            --app-cream: #FDF9F3;
            --app-card: #FFFFFF;
            --app-input: #F7F3EC;
            --app-accent-gold: #E2B93B;
            --app-text-muted: #8A7B70;
        }

        body {
            background-color: var(--app-cream);
            color: var(--app-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 80px;
        }

        .app-header {
            background-color: var(--app-dark);
            color: #fff;
            padding: 20px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .header-title { font-size: 22px; font-weight: 700; line-height: 1.2; }
        .header-subtitle { font-size: 13px; opacity: 0.8; }

        .app-card {
            background-color: #fff;
            border-radius: 18px;
            border: 1px solid #EFEAE1;
            padding: 16px;
            box-shadow: 0 4px 12px rgba(59, 35, 20, 0.03);
            margin-bottom: 16px;
        }

        .banner-card {
            background-color: #fff;
            border-radius: 18px;
            border: 1px solid #EFEAE1;
            padding: 16px;
        }

        .action-card {
            background: #fff;
            border: 1px solid #EFEAE1;
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: 0.2s;
        }
        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.05);
        }

        .icon-box-gold {
            background-color: #FDF3D6; color: #B2820A;
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin: 0 auto 8px auto;
        }

        .icon-box-blue {
            background-color: #E6F0FA; color: #1E6091;
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin: 0 auto 8px auto;
        }

        .icon-box-green {
            background-color: #E8F5E9; color: #2E7D32;
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin: 0 auto 8px auto;
        }

        .app-btn-dark {
            background-color: var(--app-dark);
            color: #fff; border-radius: 14px;
            padding: 12px 20px; font-weight: 600; border: none;
        }
        .app-btn-dark:hover { background-color: #2A180D; color: #fff; }

        .filter-chip {
            background-color: #fff; border: 1px solid #EFEAE1;
            border-radius: 12px; padding: 6px 16px;
            font-size: 14px; font-weight: 600; color: var(--app-dark); cursor: pointer;
        }
        .filter-chip.active {
            background-color: var(--app-dark); color: #fff; border-color: var(--app-dark);
        }

        .app-input {
            background-color: var(--app-input);
            border: 1px solid #EFEAE1; border-radius: 12px;
            padding: 10px 14px; color: var(--app-dark);
        }

        .donut-chart {
            width: 100px; height: 100px; border-radius: 50%;
            background: conic-gradient(var(--app-accent-gold) 0% 100%, #EFEAE1 100% 100%);
            display: flex; align-items: center; justify-content: center; margin-right: 15px;
        }
        .donut-inner {
            width: 70px; height: 70px; background: #fff; border-radius: 50%;
            display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;
        }

        .chat-bubble-self { background-color: var(--app-dark); color: white; border-radius: 12px 12px 0px 12px; }
        .chat-bubble-other { background-color: #EFEAE1; color: var(--app-dark); border-radius: 12px 12px 12px 0px; }
    </style>
</head>
<body>

    @include('components.header')

    <div class="container" style="max-width: 600px;">
        @yield('content')
    </div>

    @include('components.bottom-nav')

    <!-- MODAL COMPONENTS -->
    @include('components.modal-create-task')
    @include('components.modal-add-employee')
    @include('components.modal-add-dept')
    @include('components.modal-task-chat')
    @include('components.modal-notifications')

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user') || '{}');

        if (!token) window.location.href = '/login';

        if (user && user.name) {
            document.getElementById('userNameText').innerText = user.name;
            document.getElementById('userSubtitle').innerText = `${user.name} • ${user.role || 'Administration'}`;
        }

        const headers = {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };

        let allTasks = [];
        let allEmployees = [];
        let allDepartments = [];
        let activeTaskIdForChat = null;

        function switchTab(tab) {
            document.querySelectorAll('.tab-content-sec').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.nav-item-btn').forEach(el => el.classList.remove('active'));

            document.getElementById(`sec-${tab}`).classList.remove('d-none');
            document.getElementById(`btn-sec-${tab}`).classList.add('active');

            if (tab === 'team') {
                document.getElementById('appHeaderTitle').innerHTML = 'Workforce<br>Management';
            } else {
                document.getElementById('appHeaderTitle').innerHTML = 'Task<br>Management';
            }
        }

        async function apiFetch(url, options = {}) {
            options.headers = headers;
            const res = await fetch(url, options);
            if (res.status === 401) {
                logout();
                return null;
            }
            return res;
        }

        async function fetchDashboard() {
            try {
                const res = await apiFetch('/api/dashboard');
                if (res && res.ok) {
                    const data = await res.json();
                    if(data.tasks_summary || data.data) {
                        const summary = data.tasks_summary || data.data;
                        document.getElementById('statOverviewTotal').innerText = summary.total || 0;
                        document.getElementById('statPendingCount').innerText = summary.pending || 0;
                        document.getElementById('statInProgressCount').innerText = summary.in_progress || 0;
                        document.getElementById('statCompletedCount').innerText = summary.completed || 0;
                    }
                }
            } catch (e) { console.error("Dashboard Stats Error:", e); }
        }

        async function fetchTasks() {
            try {
                let res = await apiFetch('/api/tasks');
                if (!res || !res.ok) {
                    res = await apiFetch('/api/employee/tasks');
                }

                if (res && res.ok) {
                    const data = await res.json();
                    allTasks = Array.isArray(data) ? data : (data.data || []);
                    renderTaskList(allTasks);

                    const total = allTasks.length;
                    const pending = allTasks.filter(t => (t.status || 'pending').toLowerCase() === 'pending').length;
                    const inProgress = allTasks.filter(t => (t.status || '').toLowerCase() === 'in_progress').length;
                    const completed = allTasks.filter(t => (t.status || '').toLowerCase() === 'completed').length;

                    document.getElementById('statOverviewTotal').innerText = total;
                    document.getElementById('statPendingCount').innerText = pending;
                    document.getElementById('statInProgressCount').innerText = inProgress;
                    document.getElementById('statCompletedCount').innerText = completed;
                }
            } catch (e) {
                console.error("Fetch Tasks Error:", e);
                document.getElementById('taskListContainer').innerHTML = '<p class="text-center text-danger my-4">Failed to load tasks.</p>';
            }
        }

        function renderTaskList(tasks) {
            const container = document.getElementById('taskListContainer');
            container.innerHTML = '';

            if (!tasks || tasks.length === 0) {
                container.innerHTML = '<p class="text-center text-muted my-4">No tasks found.</p>';
                return;
            }

            tasks.forEach(t => {
                const st = (t.status || 'pending').toLowerCase();
                const badgeBg = st === 'completed' ? 'bg-success' : (st === 'in_progress' ? 'bg-primary' : 'bg-warning text-dark');
                
                container.innerHTML += `
                    <div class="app-card d-flex justify-content-between align-items-center mb-2" onclick="openTaskChatModal(${t.id}, '${t.title.replace(/'/g, "\\'")}', '${t.status}')" style="cursor:pointer;">
                        <div>
                            <h6 class="fw-bold m-0">${t.title}</h6>
                            <small class="text-muted">${t.description || 'No description provided'}</small>
                        </div>
                        <span class="badge ${badgeBg} px-3 py-2 rounded-pill text-capitalize">${t.status || 'pending'}</span>
                    </div>
                `;
            });
        }

        function filterTasks(status, element) {
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            element.classList.add('active');

            if (status === 'all') {
                renderTaskList(allTasks);
            } else {
                renderTaskList(allTasks.filter(t => (t.status || 'pending').toLowerCase() === status));
            }
        }

        async function fetchEmployeesAndDepts() {
            try {
                const [empRes, deptRes] = await Promise.all([
                    apiFetch('/api/employees'),
                    apiFetch('/api/departments')
                ]);

                if (empRes && empRes.ok) {
                    const empData = await empRes.json();
                    allEmployees = Array.isArray(empData) ? empData : (empData.data || []);
                }

                if (deptRes && deptRes.ok) {
                    const deptData = await deptRes.json();
                    allDepartments = Array.isArray(deptData) ? deptData : (deptData.data || []);
                }

                document.getElementById('statTeamTotal').innerText = allEmployees.length;
                document.getElementById('statDeptsTotal').innerText = allDepartments.length;

                const empSelect = document.getElementById('assignedEmpSelect');
                if (empSelect) {
                    empSelect.innerHTML = '<option value="">Select Employee...</option>';
                    allEmployees.forEach(e => {
                        empSelect.innerHTML += `<option value="${e.id}">${e.name} (${e.email})</option>`;
                    });
                }

                const deptSelect = document.getElementById('empDeptSelect');
                if (deptSelect) {
                    deptSelect.innerHTML = '<option value="">Select Department...</option>';
                    allDepartments.forEach(d => {
                        deptSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                    });
                }

                const container = document.getElementById('employeeListContainer');
                if (container) {
                    container.innerHTML = '';
                    if (allEmployees.length === 0) {
                        container.innerHTML = '<p class="text-center text-muted my-4">No team members found.</p>';
                        return;
                    }

                    allEmployees.forEach(e => {
                        container.innerHTML += `
                            <div class="app-card mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold m-0">${e.name}</h6>
                                        <small class="text-muted">${e.email}</small>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="badge bg-light text-dark border fw-bold">EMPLOYEE</span>
                                        <button class="btn btn-sm btn-outline-danger border-0" onclick="deleteEmployee(${e.id})"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }

            } catch (e) { console.error("Employees Fetch Error:", e); }
        }

        async function deleteEmployee(id) {
            if (!confirm('Are you sure you want to delete this employee?')) return;
            const res = await apiFetch(`/api/employees/${id}`, { method: 'DELETE' });
            if (res && res.ok) fetchEmployeesAndDepts();
        }

        async function fetchNotifications() {
            try {
                const res = await apiFetch('/api/notifications');
                if (res && res.ok) {
                    const data = await res.json();
                    const notifs = Array.isArray(data) ? data : (data.data || []);
                    const unread = notifs.filter(n => !n.read_at).length;
                    document.getElementById('notifBadge').innerText = unread;

                    const container = document.getElementById('notifListContainer');
                    if (container) {
                        container.innerHTML = '';
                        if (notifs.length === 0) {
                            container.innerHTML = '<p class="text-center text-muted my-3">No notifications.</p>';
                            return;
                        }

                        notifs.forEach(n => {
                            container.innerHTML += `
                                <div class="p-2 mb-2 rounded bg-light border d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="m-0 small fw-bold">${n.data?.message || 'New Alert'}</p>
                                        <small class="text-muted" style="font-size:10px;">${new Date(n.created_at).toLocaleString()}</small>
                                    </div>
                                    ${!n.read_at ? `<button class="btn btn-xs btn-primary py-0" style="font-size:10px;" onclick="markNotifRead('${n.id}')">Mark Read</button>` : ''}
                                </div>
                            `;
                        });
                    }
                }
            } catch (e) { console.error("Notifications Fetch Error:", e); }
        }

        async function markNotifRead(id) {
            await apiFetch(`/api/notifications/${id}/read`, { method: 'POST' });
            fetchNotifications();
        }

        async function openTaskChatModal(taskId, title, status) {
            activeTaskIdForChat = taskId;
            document.getElementById('chatTaskTitle').innerText = title;
            document.getElementById('chatTaskStatus').innerText = `Status: ${status || 'Pending'}`;
            new bootstrap.Modal(document.getElementById('taskChatModal')).show();
            loadComments(taskId);
        }

        async function loadComments(taskId) {
            const container = document.getElementById('chatMessagesContainer');
            container.innerHTML = '<p class="text-center text-muted">Loading comments...</p>';
            try {
                let res = await apiFetch(`/api/tasks/${taskId}/comments`);
                if (!res || !res.ok) res = await apiFetch(`/api/admin/tasks/${taskId}/comments`);

                if (res && res.ok) {
                    const data = await res.json();
                    const comments = Array.isArray(data) ? data : (data.data || []);
                    container.innerHTML = '';
                    if (comments.length === 0) {
                        container.innerHTML = '<p class="text-center text-muted my-4">No comments yet. Start the conversation!</p>';
                        return;
                    }
                    comments.forEach(c => {
                        const isSelf = c.user_id == user.id;
                        container.innerHTML += `
                            <div class="d-flex flex-column ${isSelf ? 'align-items-end' : 'align-items-start'} mb-2">
                                <span class="text-muted" style="font-size:10px;">${c.user?.name || 'User'}</span>
                                <div class="p-2 small ${isSelf ? 'chat-bubble-self' : 'chat-bubble-other'}">
                                    ${c.comment || c.message}
                                </div>
                            </div>
                        `;
                    });
                    container.scrollTop = container.scrollHeight;
                }
            } catch (e) { container.innerHTML = '<p class="text-center text-danger">Failed to load comments.</p>'; }
        }

        function openCreateTaskModal() { new bootstrap.Modal(document.getElementById('createTaskModal')).show(); }
        function openAddEmployeeModal() { new bootstrap.Modal(document.getElementById('addEmpModal')).show(); }
        function openAddDeptModal() { new bootstrap.Modal(document.getElementById('addDeptModal')).show(); }
        function openNotificationsModal() { new bootstrap.Modal(document.getElementById('notifModal')).show(); }

        function logout() {
            apiFetch('/api/auth/logout', { method: 'POST' }).finally(() => {
                localStorage.clear();
                window.location.href = '/login';
            });
        }

        fetchDashboard();
        fetchTasks();
        fetchEmployeesAndDepts();
        fetchNotifications();
    </script>
    @stack('scripts')
</body>
</html>