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
            --bg-cream: #FAF7F2;
            --card-bg: #FFFFFF;
            --input-bg: #F4EFE6;
            --border-color: #E2D9CC;
            --text-dark: #2B1D14;
            --text-muted: #786C60;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-cream);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        .portal-wrapper {
            min-height: 100vh;
            display: flex;
            width: 100%;
        }

        /* ----------------------------------------------------
           LEFT BRAND & VIDEO PANEL
        ---------------------------------------------------- */
        .left-brand-panel {
            flex: 1.2;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 50px 60px;
            color: #ffffff;
            overflow: hidden;
            background-color: #120A05;
        }

        .bg-video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: 1;
            transform: translate(-50%, -50%);
            object-fit: cover;
            filter: brightness(0.55) contrast(1.15);
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                135deg, 
                rgba(35, 20, 10, 0.85) 0%, 
                rgba(18, 10, 5, 0.75) 50%,
                rgba(0, 0, 0, 0.90) 100%
            );
            z-index: 2;
        }

        .left-brand-content {
            position: relative;
            z-index: 4;
        }

        .brand-logo-img {
            max-height: 65px;
            width: auto;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.3));
        }

        .brand-badge {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #F3E8DC;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .brand-hero-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.5px;
            color: #FFFFFF;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .hero-subtext {
            font-size: 1rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.85);
            max-width: 480px;
        }

        .feature-chip {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 12.5px;
            font-weight: 500;
            color: #F3E8DC;
        }

        .tiger-mascot {
            position: absolute;
            bottom: -10px;
            right: 40px;
            z-index: 3;
            max-width: 250px;
            height: auto;
            pointer-events: none;
            filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.7));
            animation: floatingMascot 4s ease-in-out infinite;
        }

        @keyframes floatingMascot {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* ----------------------------------------------------
           RIGHT FORM PANEL
        ---------------------------------------------------- */
        .right-form-panel {
            flex: 0.8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 30px;
            background: linear-gradient(180deg, var(--bg-cream) 0%, #F3ECDF 100%);
        }

        .web-login-card {
            width: 100%;
            max-width: 420px;
            background: var(--card-bg);
            border-radius: 24px;
            padding: 40px 36px;
            box-shadow: 0 20px 45px rgba(59, 35, 20, 0.08);
            border: 1px solid var(--border-color);
        }

        .icon-circle {
            width: 54px;
            height: 54px;
            background-color: rgba(217, 119, 6, 0.12);
            color: var(--brand-gold);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .role-btn-group {
            background-color: var(--input-bg);
            border-radius: 14px;
            padding: 5px;
            border: 1px solid var(--border-color);
        }

        .role-btn {
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 600;
            color: var(--text-muted);
            background: transparent;
            width: 50%;
            transition: all 0.25s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .role-btn.active {
            background-color: var(--brand-primary);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(59, 35, 20, 0.25);
        }

        .form-label-custom {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .app-input-wrapper {
            background-color: var(--input-bg);
            border: 1.5px solid var(--border-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            padding: 0 14px;
            transition: all 0.2s ease;
        }

        .app-input-wrapper:focus-within {
            background-color: #FFFFFF;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(59, 35, 20, 0.1);
        }

        .app-input-wrapper i {
            color: var(--text-muted);
            font-size: 16px;
            margin-right: 10px;
        }

        .app-input {
            background: transparent;
            border: none;
            padding: 12px 0;
            color: var(--text-dark);
            width: 100%;
            font-size: 14px;
            font-weight: 500;
        }

        .app-input:focus {
            outline: none;
            box-shadow: none;
        }

        .btn-app-dark {
            background-color: var(--brand-primary);
            color: #FFFFFF;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            border: none;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .btn-app-dark:hover {
            background-color: var(--brand-primary-hover);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(59, 35, 20, 0.2);
        }

        .btn-app-dark:active {
            transform: translateY(0);
        }

        /* ----------------------------------------------------
           RESPONSIVE BREAKPOINTS
        ---------------------------------------------------- */
        @media (max-width: 991px) {
            .portal-wrapper {
                flex-direction: column;
            }
            .left-brand-panel {
                padding: 36px 20px;
                min-height: 280px;
                justify-content: center;
                align-items: center;
                text-align: center;
            }
            .brand-hero-title {
                font-size: 2.2rem;
            }
            .hero-subtext, 
            .feature-chips-container {
                display: none;
            }
            .tiger-mascot {
                max-width: 140px;
                right: 15px;
                bottom: -10px;
                opacity: 0.85;
            }
            .right-form-panel {
                padding: 30px 16px;
                margin-top: -20px;
                position: relative;
                z-index: 6;
            }
        }
    </style>
</head>
<body>

    <div class="portal-wrapper">
        <!-- Left Side: Video Background & Hero Content -->
        <div class="left-brand-panel">
            <!-- Background Video -->
            <video class="bg-video" autoplay loop muted playsinline poster="{{ asset('images/bg.png') }}">
                <source src="{{ asset('video/TMS_video.mp4') }}" type="video/mp4">
            </video>
            
            <div class="video-overlay"></div>

            <!-- Top Header Logo & Badge -->
            <div class="left-brand-content d-flex justify-content-between align-items-center mb-4">
                <div class="brand-logo-wrapper">
                    <img src="{{ asset('images/logo.png') }}" alt="ECL Logo" class="brand-logo-img">
                </div>
                <div class="brand-badge d-none d-sm-inline-flex">
                    <i class="bi bi-shield-check text-warning"></i> Official Internal Portal
                </div>
            </div>

            <!-- Hero Title & Features -->
            <div class="left-brand-content my-auto">
                <h1 class="brand-hero-title mb-3">
                    ECL Task Management<br>System
                </h1>
                <p class="hero-subtext mb-4 d-none d-md-block">
                    Centralized platform designed to streamline departmental workflows, track administrative task lifecycles, and enable real-time operational efficiency.
                </p>

                <div class="d-none d-md-flex flex-wrap gap-2 feature-chips-container">
                    <span class="feature-chip"><i class="bi bi-shield-lock-fill text-warning me-1"></i> Role-Based Access</span>
                    <span class="feature-chip"><i class="bi bi-diagram-3-fill text-info me-1"></i> Workflow Management</span>
                    <span class="feature-chip"><i class="bi bi-speedometer2 text-success me-1"></i> Live Tracking</span>
                </div>
            </div>

            <!-- Bottom Footer Text -->
            <div class="left-brand-content d-flex justify-content-center align-items-center pt-3">
                <div class="d-flex align-items-center gap-2 text-white-50 small font-monospace tracking-widest">
                    <span>ENERGY</span> • <span>COMMITMENT</span> • <span>LEADERSHIP</span>
                </div>
            </div>

            <!-- Mascot Asset -->
            <img src="{{ asset('images/Tiger.png') }}" alt="Tiger Engineer Mascot" class="tiger-mascot">
        </div>

        <!-- Right Side: Login Form Card -->
        <div class="right-form-panel">
            <div class="web-login-card">
                
                <div class="text-center mb-4">
                    <div class="icon-circle mb-3">
                        <i class="bi bi-shield-lock-fill fs-3"></i>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: var(--brand-primary);">TMS PORTAL LOGIN</h4>
                    <p class="text-muted small m-0">Select your authorization domain to continue</p>
                </div>

                <!-- Role Switcher -->
                <div class="role-btn-group d-flex mb-4">
                    <button type="button" class="role-btn active" id="btnEmp" onclick="setRole('employee')">
                        <i class="bi bi-person-fill"></i> Employee
                    </button>
                    <button type="button" class="role-btn" id="btnAdmin" onclick="setRole('admin')">
                        <i class="bi bi-shield-fill"></i> Admin
                    </button>
                </div>

                <!-- Alert Feedback Container -->
                <div id="alertBox" class="alert alert-danger d-none py-2 px-3 text-center mb-3" style="font-size: 13px; border-radius: 10px;"></div>

                <!-- Form Inputs -->
                <form id="loginForm">
                    <div class="mb-3">
                        <label class="form-label-custom">Official Email Address</label>
                        <div class="app-input-wrapper">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" id="email" class="app-input" placeholder="e.g. user@coalindia.in" required autocomplete="email">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Account Password</label>
                        <div class="app-input-wrapper">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" id="password" class="app-input" placeholder="••••••••" required autocomplete="current-password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-app-dark w-100 shadow-sm" id="submitBtn">
                        <span id="btnText">Login to Portal</span>
                        <div id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></div>
                    </button>
                </form>

                <div class="text-center mt-4 pt-2">
                    <span class="text-muted" style="font-size: 11.5px;">
                        <i class="bi bi-shield-check text-success me-1"></i> Protected by ECL Cyber-Security Policy
                    </span>
                </div>

            </div>
        </div>
    </div>

    <!-- JavaScript Handling -->
    <script>
        let currentRole = 'employee';

        function setRole(role) {
            currentRole = role;
            document.getElementById('btnEmp').classList.toggle('active', role === 'employee');
            document.getElementById('btnAdmin').classList.toggle('active', role === 'admin');
        }

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const alertBox = document.getElementById('alertBox');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            // Reset Alert & Show Loading Spinner State
            alertBox.classList.add('d-none');
            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        role: currentRole
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Check user roles payload from API response
                    const userRoles = (data.role || '').split(',').map(r => r.trim().toLowerCase());
                    
                    if (currentRole === 'admin' && !userRoles.includes('admin') && !data.has_admin_access) {
                        alertBox.textContent = 'Access Denied: You do not have Admin privileges.';
                        alertBox.classList.remove('d-none');
                        return;
                    }

                    // Store Tokens securely in LocalStorage
                    const tokenToSave = (currentRole === 'admin' ? data.admin_token : data.employee_token) || data.token;
                    
                    if (tokenToSave) {
                        localStorage.setItem('auth_token', tokenToSave);
                    }
                    if (data.user) {
                        localStorage.setItem('user_info', JSON.stringify(data.user));
                    }

                    // Redirect dynamically based on active domain role
                    if (currentRole === 'admin') {
                        window.location.href = '/admin/dashboard';
                    } else {
                        window.location.href = '/employee/dashboard';
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
                // Restore Button State
                btnText.classList.remove('d-none');
                btnSpinner.classList.add('d-none');
            }
        });
    </script>
</body>
</html>