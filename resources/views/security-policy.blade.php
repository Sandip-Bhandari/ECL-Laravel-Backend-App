<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ECL Task Management System purpose, usage guidance, security and privacy information, and terms of use.">
    <title>Security, Privacy &amp; Terms - ECL TMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(145deg, #12181f, #25313c);
            color: #17212b;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .policy-header { color: #fff; }
        .policy-card { border: 0; border-radius: 1rem; }
        .policy-icon {
            display: inline-flex;
            width: 2.5rem;
            height: 2.5rem;
            align-items: center;
            justify-content: center;
            border-radius: .75rem;
            background: #fff4d1;
            color: #856404;
            font-size: 1.2rem;
        }
        .policy-card h2 { font-size: 1.15rem; }
        .policy-card p:last-child, .policy-card ul:last-child { margin-bottom: 0; }
    </style>
</head>
<body>
    <main class="container py-4 py-md-5" style="max-width: 900px">
        <header class="policy-header d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <p class="text-warning fw-semibold mb-1">ECL Task Management System</p>
                <h1 class="h2 fw-bold mb-1">Security, Privacy &amp; Terms</h1>
                <p class="text-white-50 mb-0">Purpose, safe use, and important account guidance</p>
            </div>
            <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Back to login
            </a>
        </header>

        <div class="alert alert-warning border-0 rounded-4" role="note">
            <i class="bi bi-shield-exclamation me-2"></i>
            <strong>Keep your account private.</strong> Never share your password, login code, or other credentials with anyone.
        </div>

        <div class="row g-3">
            <section class="col-12">
                <article class="card policy-card shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 align-items-start">
                            <span class="policy-icon"><i class="bi bi-bullseye"></i></span>
                            <div>
                                <h2 class="fw-bold">Purpose of the app</h2>
                                <p class="mb-0">ECL TMS is intended to help teams assign and manage work, follow task status and deadlines, and discuss task-related updates in one place. Use it only for legitimate, work-related purposes permitted by your organization.</p>
                            </div>
                        </div>
                    </div>
                </article>
            </section>

            <section class="col-12 col-md-6">
                <article class="card policy-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 align-items-start">
                            <span class="policy-icon"><i class="bi bi-journal-check"></i></span>
                            <div>
                                <h2 class="fw-bold">How to use ECL TMS</h2>
                                <ol class="ps-3 mb-0">
                                    <li>Sign in using the account and role provided to you.</li>
                                    <li>Review your assigned tasks, descriptions, and due dates.</li>
                                    <li>Update task progress accurately and use the task discussion for relevant work updates.</li>
                                    <li>Check notifications for assignments, messages, and status changes.</li>
                                    <li>Sign out when finished, especially on a shared device.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </article>
            </section>

            <section class="col-12 col-md-6">
                <article class="card policy-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 align-items-start">
                            <span class="policy-icon"><i class="bi bi-person-lock"></i></span>
                            <div>
                                <h2 class="fw-bold">Account security</h2>
                                <ul class="ps-3 mb-0">
                                    <li>Keep credentials confidential; do not share or reuse them on untrusted services.</li>
                                    <li>Use only your own authorized account. Do not attempt to access another person's account or data.</li>
                                    <li>Access is governed by your assigned role and permissions. Authorized users may be able to view work progress or take permitted task actions.</li>
                                    <li>Do not assume that task updates or other actions are private from authorized managers or administrators.</li>
                                    <li>Report suspected account access or security issues through your organization's approved support channel.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </article>
            </section>

            <section class="col-12 col-md-6">
                <article class="card policy-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 align-items-start">
                            <span class="policy-icon"><i class="bi bi-eye"></i></span>
                            <div>
                                <h2 class="fw-bold">Privacy and work information</h2>
                                <p>Task details, status updates, discussions, and account information may be visible to people authorized for the relevant work or administration. Share only information needed for work, keep messages professional, and do not post passwords, login codes, or unrelated sensitive personal information.</p>
                                <p class="mb-0">Use the application and its data in accordance with your organization's policies. Contact your organization for questions about data handling, retention, or access.</p>
                            </div>
                        </div>
                    </div>
                </article>
            </section>

            <section class="col-12 col-md-6">
                <article class="card policy-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 align-items-start">
                            <span class="policy-icon"><i class="bi bi-file-earmark-text"></i></span>
                            <div>
                                <h2 class="fw-bold">Terms of use</h2>
                                <ul class="ps-3 mb-0">
                                    <li>Use only the access granted to you and follow your organization's instructions.</li>
                                    <li>Enter truthful, work-related updates; do not misuse, disrupt, or try to bypass the service's access controls.</li>
                                    <li>Task status changes and messages may be recorded and reviewed by authorized users.</li>
                                    <li>Access and features may be managed by the organization. These notes supplement, and do not replace, official organizational policies.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </article>
            </section>
        </div>

        <footer class="text-center text-white-50 mt-4">
            <p class="mb-1">Created by Intern Sandip Bhandari</p>
            <small>This page provides general app guidance; follow your organization's official security and privacy policies.</small>
        </footer>
    </main>
</body>
</html>
