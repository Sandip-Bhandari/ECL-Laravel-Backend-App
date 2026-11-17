<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ECL Task Management System - Portal Login</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #3B2314;
            --brand-primary-hover: #26160C;
            --brand-gold: #D97706;
            --card-glass-bg: rgba(255, 255, 255, 0.88);
            --input-bg: rgba(244, 239, 230, 0.85);
            --border-color: rgba(226, 217, 204, 0.9);
            --text-dark: #2B1D14;
            --text-muted: #786C60;
        }

        * {
            box-sizing: border-box;
        }

        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #1a0f0a;
        }

        /* Fullscreen Background Video */
        .bg-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            z-index: 0;
            pointer-events: none;
            filter: brightness(0.65) contrast(1.1) saturate(1.05);
        }

        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: linear-gradient(
                135deg, 
                rgba(15, 8, 4, 0.55) 0%, 
                rgba(25, 14, 8, 0.40) 50%,
                rgba(5, 3, 1, 0.65) 100%
            );
            z-index: 1;
            pointer-events: none;
        }

        /* App Container Overlay */
        .portal-container {
            position: relative;
            z-index: 10;
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        /* Top-Left Brand Logo & Title */
        .top-left-brand {
            position: absolute;
            top: 30px;
            left: 40px;
            display: inline-flex;
            align-items: center;
            gap: 14px;
            background: rgba(13, 7, 4, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            padding: 8px 18px 8px 12px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            z-index: 12;
        }

        .brand-logo-img {
            max-height: 34px;
            width: auto;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.6));
        }

        .brand-divider {
            width: 1px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
        }

        .brand-header-title {
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.25;
            color: #FFFFFF;
            margin: 0;
            letter-spacing: -0.2px;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.6);
        }

        .brand-header-title span {
            color: #FBBF24;
            font-weight: 800;
        }

        /* Bottom Area Container */
        .bottom-center-area {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 12;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .system-version {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.6);
            font-family: monospace;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 3px rgba(0,0,0,0.8);
        }

        .system-version a {
            color: #FBBF24;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .system-version a:hover {
            color: #FFFFFF;
            text-decoration: underline;
        }

        /* Centered Glassmorphic Login Form */
        .center-form-container {
            width: 100%;
            max-width: 400px;
            z-index: 15;
            margin: auto;
        }

        .web-login-card {
            background: var(--card-glass-bg);
            border-radius: 20px;
            padding: 26px 28px 20px 28px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.7);
            border: 1.5px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
        }

        .icon-circle {
            width: 44px;
            height: 44px;
            background-color: rgba(217, 119, 6, 0.15);
            color: var(--brand-gold);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .role-btn-group {
            background-color: var(--input-bg);
            border-radius: 10px;
            padding: 3px;
            border: 1px solid var(--border-color);
        }

        .role-btn {
            border: none;
            border-radius: 8px;
            padding: 7px 10px;
            font-weight: 600;
            color: var(--text-muted);
            background: transparent;
            width: 50%;
            transition: all 0.25s ease;
            font-size: 12.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .role-btn.active {
            background-color: var(--brand-primary);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(59, 35, 20, 0.25);
        }

        .form-label-custom {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .app-input-wrapper {
            background-color: var(--input-bg);
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            padding: 0 11px;
            transition: all 0.2s ease;
        }

        .app-input-wrapper:focus-within {
            background-color: #FFFFFF;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(59, 35, 20, 0.1);
        }

        .app-input-wrapper i {
            color: var(--text-muted);
            font-size: 14px;
            margin-right: 8px;
        }

        .app-input {
            background: transparent;
            border: none;
            padding: 9px 0;
            color: var(--text-dark);
            width: 100%;
            font-size: 13px;
            font-weight: 500;
        }

        .app-input:focus {
            outline: none;
            box-shadow: none;
        }

        .btn-app-dark {
            background-color: var(--brand-primary);
            color: #FFFFFF;
            border-radius: 10px;
            padding: 10.5px;
            font-weight: 700;
            border: none;
            font-size: 13.5px;
            transition: all 0.2s ease;
        }

        .btn-app-dark:hover {
            background-color: var(--brand-primary-hover);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(59, 35, 20, 0.2);
        }

        .app-link-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--input-bg);
            border: 1.5px dashed var(--border-color);
            border-radius: 10px;
            padding: 7px 11px;
            text-decoration: none;
            color: var(--brand-primary);
            transition: all 0.3s ease;
        }

        .app-link-card:hover {
            background-color: rgba(234, 245, 234, 0.95);
            border: 1.5px solid #28A745;
            color: #155724;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.18);
        }

        .app-link-card:hover i.bi-android2, 
        .app-link-card:hover i.bi-download {
            color: #198754 !important;
        }

        @media (max-width: 991px) {
            body, html {
                overflow-y: auto;
                height: auto;
            }
            .portal-container {
                flex-direction: column;
                height: auto;
                padding: 20px;
                justify-content: flex-start;
            }
            .top-left-brand {
                position: relative;
                top: 0;
                left: 0;
                margin-bottom: 15px;
            }
            .center-form-container {
                margin: 15px auto;
                max-width: 100%;
            }
            .bottom-center-area {
                position: relative;
                bottom: 0;
                left: 0;
                transform: none;
                margin: 15px auto;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <!-- Fullscreen Background Video -->
    <video class="bg-video" autoplay loop muted playsinline poster="{{ asset('images/bg.png') }}">
        <source src="{{ asset('video/TMS_tiger_left.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="video-overlay"></div>

    <div class="portal-container">
        <!-- Top-Left Brand Logo & Title -->
        <div class="top-left-brand">
            <img src="{{ asset('images/logo.png') }}" alt="ECL Logo" class="brand-logo-img">
            <div class="brand-divider"></div>
            <h1 class="brand-header-title"><span>Task Management</span><br>System</h1>
        </div>

        <!-- Bottom-Center Core Values & Version -->
        <div class="bottom-center-area">
            <div class="system-version">Version 1.0.0 &bull; Developed by <a href="https://github.com/sandip-bhandari" target="_blank" rel="noopener noreferrer">Sandip</a></div>
        </div>

        <!-- Centered Glassmorphic Login Form -->
        <div class="center-form-container">
            <div class="web-login-card">
                
                <div class="text-center mb-2">
                    <div class="icon-circle mb-1">
                        <i class="bi bi-shield-lock-fill fs-5"></i>
                    </div>
                    <h5 class="fw-bold mb-0" style="color: var(--brand-primary); font-size: 1.15rem;">PORTAL LOGIN</h5>
                    <p class="text-muted small m-0" style="font-size: 11.5px;">Select your authorization domain to continue</p>
                </div>

                <!-- Role Switcher -->
                <div class="role-btn-group d-flex mb-2">
                    <button type="button" class="role-btn active" id="btnEmp" onclick="setRole('employee')">
                        <i class="bi bi-person-fill"></i> Employee
                    </button>
                    <button type="button" class="role-btn" id="btnAdmin" onclick="setRole('admin')">
                        <i class="bi bi-shield-fill"></i> Admin
                    </button>
                </div>

                <!-- Alert Feedback Container -->
                <div id="alertBox" class="alert alert-danger d-none py-1 px-2 text-center mb-2" style="font-size: 12px; border-radius: 8px;"></div>

                <!-- Form Inputs -->
                <form id="loginForm">
                    <div class="mb-2">
                        <label class="form-label-custom">Official Email Address</label>
                        <div class="app-input-wrapper">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" id="email" class="app-input" placeholder="e.g. user@coalindia.in" required autocomplete="email">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label-custom">Account Password</label>
                        <div class="app-input-wrapper">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" id="password" class="app-input" placeholder="••••••••" required autocomplete="current-password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-app-dark w-100 shadow-sm mb-2" id="submitBtn">
                        <span id="btnText">Login to Portal</span>
                        <div id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></div>
                    </button>
                </form>

                <!-- Direct APK Download Link Card -->
                <a href="{{ asset('downloads/ECL_TMS.apk') }}" download="ECL_TMS.apk" class="app-link-card mb-1">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-android2 fs-5 text-success"></i>
                        <div class="text-start">
                            <div class="fw-bold" style="font-size: 11.5px; line-height: 1.1;">Download Android App</div>
                            <div class="text-muted" style="font-size: 9.5px;">Official ECL TMS Mobile Client</div>
                        </div>
                    </div>
                    <i class="bi bi-download fs-6 opacity-75"></i>
                </a>

                <div class="text-center pt-1">
                    <a href="{{ route('security-policy') }}" class="text-muted text-decoration-none" style="font-size: 10px;">
                        <i class="bi bi-shield-check text-success me-1"></i> Protected by ECL Cyber-Security Policy
                        <span class="d-block mt-1 text-decoration-underline">Security, privacy, terms &amp; how to use</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
    let currentRole = 'employee';

    function setRole(role) {
        currentRole = role;
        document.getElementById('btnEmp').classList.toggle('active', role === 'employee');
        document.getElementById('btnAdmin').classList.toggle('active', role === 'admin');
        const password = document.getElementById('password');
        password.required = role === 'admin';
        password.placeholder = role === 'admin' ? 'Enter your password' : 'Password (if enabled)';
    }
    setRole('employee');

    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const alertBox = document.getElementById('alertBox');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');

        alertBox.classList.add('d-none');
        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    email: email,
                    password: password,
                    role: currentRole
                })
            });

            const data = await response.json();

            if (response.ok) {
                const serverRole = (data.role || (data.user && data.user.role) || '').toLowerCase();
                const hasAdminAccess = data.has_admin_access || (data.user && data.user.has_admin_access);

                if (currentRole === 'admin' && !serverRole.split(',').map(role => role.trim()).some(role => ['admin', 'super_admin', 'manager'].includes(role)) && !hasAdminAccess) {
                    alertBox.textContent = 'Access Denied: You do not have Admin privileges.';
                    alertBox.classList.remove('d-none');
                    return;
                }

                const tokenToSave = currentRole === 'admin'
                    ? (data.admin_token || data.token || data.access_token)
                    : (data.employee_token || data.token || data.access_token);
                if (!tokenToSave) {
                    alertBox.textContent = 'Login response did not include an access token.';
                    alertBox.classList.remove('d-none');
                    return;
                }

                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_info');
                localStorage.setItem('auth_token', tokenToSave);
                if (data.user) localStorage.setItem('user_info', JSON.stringify(data.user));

                if (currentRole === 'admin') {
                    window.location.replace('/admin/dashboard');
                } else {
                    window.location.replace('/employee/dashboard');
                }

            } else {
                let errorMsg = data.message || 'Invalid email or password.';
                if (data.errors && data.errors.email) {
                    errorMsg = data.errors.email[0];
                }
                alertBox.textContent = errorMsg;
                alertBox.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Login Error:', error);
            alertBox.textContent = 'Server communication error. Please try again.';
            alertBox.classList.remove('d-none');
        } finally {
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
        }
    });
</script>
</body>
</html>