@extends('layouts.app')

@section('content')

<style>
    /* Enterprise Glassmorphism Form Styling */
    .task-form-wrapper {
        animation: fadeInScale 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes fadeInScale {
        from { opacity: 0; transform: translateY(10px) scale(0.99); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .task-form-card {
        background: rgba(18, 24, 31, 0.65) !important;
        backdrop-filter: blur(24px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(180%) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        border-radius: 22px !important;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45) !important;
        color: #ffffff;
    }

    .task-form-card .form-label {
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 12.5px;
        letter-spacing: 0.4px;
        font-weight: 600;
    }

    /* Refined Glass Input Fields */
    .app-custom-input {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
        border-radius: 12px !important;
        font-size: 13.5px;
        transition: all 0.25s ease;
    }

    .app-custom-input:focus {
        background: rgba(255, 255, 255, 0.09) !important;
        border-color: #e5b72e !important;
        box-shadow: 0 0 15px rgba(229, 183, 46, 0.25) !important;
        color: #ffffff !important;
    }

    .app-custom-input::placeholder {
        color: rgba(255, 255, 255, 0.35) !important;
    }

    .app-custom-input option {
        background-color: #12181f;
        color: #ffffff;
    }

    .input-group-text-custom {
        background: rgba(255, 255, 255, 0.04) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        color: rgba(229, 183, 46, 0.85);
        border-radius: 12px 0 0 12px !important;
    }

    /* File Upload Dropzone Box */
    .file-dropzone {
        background: rgba(255, 255, 255, 0.04);
        border: 1.5px dashed rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .file-dropzone:hover {
        background: rgba(229, 183, 46, 0.06);
        border-color: #e5b72e;
    }

    /* Submit Button */
    .btn-submit-task {
        background: linear-gradient(135deg, #e5b72e 0%, #b88a14 100%) !important;
        color: #12181f !important;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        padding: 13px;
        transition: all 0.25s ease;
        box-shadow: 0 6px 20px rgba(229, 183, 46, 0.3);
    }

    .btn-submit-task:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(229, 183, 46, 0.45);
        background: linear-gradient(135deg, #f0c33f 0%, #c79516 100%) !important;
    }
</style>

<div class="container py-4 task-form-wrapper" style="max-width: 760px;">
    <div class="task-form-card mb-4 p-4 p-md-5">
        
        <!-- Header Banner -->
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="background: rgba(229, 183, 46, 0.18); color: #e5b72e; width: 46px; height: 46px; font-size: 22px;">
                    <i class="bi bi-file-earmark-plus-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold m-0 text-white" style="font-size: 18.5px; letter-spacing: 0.2px;">Issue Task Directive</h5>
                    <small class="text-warning opacity-90" style="font-size: 11.5px;">Allocate operational responsibilities & establish strict execution timelines</small>
                </div>
            </div>
            <a href="/dashboard" class="btn btn-outline-light btn-sm bg-white bg-opacity-10 border-0 text-white px-3 py-2 rounded-pill fw-semibold shadow-sm" style="font-size: 12px;">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
        </div>

        <form id="assignTaskPageForm" onsubmit="submitTaskForm(event)">
            
            <!-- Task Subject -->
            <div class="mb-3.5">
                <label class="form-label mb-1.5">Task Subject / Title *</label>
                <div class="input-group">
                    <span class="input-group-text input-group-text-custom ps-3 pe-2"><i class="bi bi-pencil-square"></i></span>
                    <input type="text" id="pageTaskTitle" class="form-control app-custom-input border-start-0 py-2.5 shadow-none" placeholder="e.g. Infrastructure Security & Cloud Migration Audit" required>
                </div>
            </div>

            <!-- Department & Employee Row -->
            <div class="row g-3 mb-3.5">
                <div class="col-md-6">
                    <label class="form-label mb-1.5">Target Department *</label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-custom ps-3 pe-2"><i class="bi bi-building"></i></span>
                        <select id="pageDeptSelect" class="form-select app-custom-input border-start-0 py-2.5 shadow-none" required>
                            <option value="">Select Department</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label mb-1.5">Assign To Employee *</label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-custom ps-3 pe-2"><i class="bi bi-person-fill"></i></span>
                        <select id="pageEmpSelect" class="form-select app-custom-input border-start-0 py-2.5 shadow-none" required>
                            <option value="">Select Employee</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Due Date & Priority Row -->
            <div class="row g-3 mb-3.5">
                <div class="col-md-6">
                    <label class="form-label mb-1.5">Target Completion Date *</label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-custom ps-3 pe-2"><i class="bi bi-calendar-event"></i></span>
                        <input type="date" id="pageTaskDueDate" class="form-control app-custom-input border-start-0 py-2.5 shadow-none" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label mb-1.5">Priority Level *</label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-custom ps-3 pe-2"><i class="bi bi-flag-fill"></i></span>
                        <select id="pageTaskPriority" class="form-select app-custom-input border-start-0 py-2.5 shadow-none">
                            <option value="Medium">Medium Priority</option>
                            <option value="High">High Priority</option>
                            <option value="Low">Low Priority</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-3.5">
                <label class="form-label mb-1.5">Directive Instructions / Description *</label>
                <textarea id="pageTaskDesc" class="form-control app-custom-input p-3 shadow-none" rows="4" placeholder="Enter detailed operational guidelines, specs and requirements..." required></textarea>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <small class="text-white-50" style="font-size: 11px;"><i class="bi bi-info-circle me-1"></i> Markdown formatting supported</small>
                    <small class="text-white-50 fw-semibold" style="font-size: 11px;" id="charCount">0/1000</small>
                </div>
            </div>

            <!-- Attach Document -->
            <div class="mb-4">
                <label class="form-label mb-1.5">Reference Document / File (Optional)</label>
                <div class="file-dropzone text-center py-3.5 px-3" onclick="document.getElementById('taskFileAttachment').click()">
                    <input type="file" id="taskFileAttachment" class="d-none" onchange="handleFileSelect(this)">
                    <div id="fileUploadPrompt">
                        <i class="bi bi-paperclip text-warning fs-5 me-1"></i>
                        <span class="small text-white fw-semibold">Click to upload reference file</span>
                        <small class="d-block text-white-50 mt-0.5" style="font-size: 10.5px;">PDF, DOCX, ZIP up to 10MB</small>
                    </div>
                    <div id="fileUploadSuccess" class="d-none text-success small fw-semibold">
                        <i class="bi bi-check-circle-fill me-1"></i> <span id="selectedFileName">file.pdf</span> selected
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn" class="btn btn-submit-task w-100 d-flex align-items-center justify-content-center gap-2" style="font-size: 14.5px;">
                <i class="bi bi-shield-check fs-5"></i> Submit & Issue Task Directive
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.replace('/login');
            return;
        }

        await loadDropdownData();

        const descInput = document.getElementById('pageTaskDesc');
        if (descInput) {
            descInput.addEventListener('input', (e) => {
                document.getElementById('charCount').textContent = `${e.target.value.length}/1000`;
            });
        }
    });

    function handleFileSelect(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            document.getElementById('selectedFileName').textContent = fileName;
            document.getElementById('fileUploadPrompt').classList.add('d-none');
            document.getElementById('fileUploadSuccess').classList.remove('d-none');
        }
    }

    async function loadDropdownData() {
        const headers = {
            'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
            'Accept': 'application/json'
        };

        try {
            const [empRes, deptRes] = await Promise.all([
                fetch('/api/employees', { headers }),
                fetch('/api/departments', { headers })
            ]);

            if (empRes.ok) {
                const empData = await empRes.json();
                const employees = Array.isArray(empData) ? empData : (empData.employees || empData.data || []);
                const empSelect = document.getElementById('pageEmpSelect');
                employees.forEach(emp => {
                    empSelect.innerHTML += `<option value="${emp.id}">${emp.name} (${emp.designation || 'Staff'})</option>`;
                });
            }

            if (deptRes.ok) {
                const deptData = await deptRes.json();
                const departments = Array.isArray(deptData) ? deptData : (deptData.departments || deptData.data || []);
                const deptSelect = document.getElementById('pageDeptSelect');
                departments.forEach(dept => {
                    deptSelect.innerHTML += `<option value="${dept.id}">${dept.name}</option>`;
                });
            }
        } catch (err) {
            console.error('Error loading form dropdowns:', err);
        }
    }

    async function submitTaskForm(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing Directive...`;

        const payload = {
            title: document.getElementById('pageTaskTitle').value,
            department_id: document.getElementById('pageDeptSelect').value,
            assigned_to: document.getElementById('pageEmpSelect').value,
            due_date: document.getElementById('pageTaskDueDate').value,
            priority: document.getElementById('pageTaskPriority').value,
            description: document.getElementById('pageTaskDesc').value,
        };

        try {
            const res = await fetch('/api/admin/tasks', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                alert('Task assigned successfully!');
                window.location.href = '/dashboard';
            } else {
                const errData = await res.json();
                alert('Error: ' + (errData.message || 'Failed to create task'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="bi bi-shield-check fs-5"></i> Submit & Issue Task Directive`;
            }
        } catch (err) {
            console.error('Submission error:', err);
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<i class="bi bi-shield-check fs-5"></i> Submit & Issue Task Directive`;
        }
    }
</script>
@endpush