<div class="modal fade" id="modalBroadcast" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold m-0"><i class="bi bi-megaphone-fill text-warning me-2"></i>Publish Notice Broadcast</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="broadcastForm">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Notice Heading</label>
                        <input type="text" class="form-control app-input" placeholder="e.g. Mandatory Safety Briefing" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Target Audience</label>
                        <select class="form-select app-input">
                            <option value="all">All Departments</option>
                            <option value="mining">Mining Officers</option>
                            <option value="admin">Administrative Staff</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Announcement Content</label>
                        <textarea class="form-control app-input" rows="3" placeholder="Type notice details..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-app-dark w-100 py-2 fw-bold">Publish Notice</button>
                </form>
            </div>
        </div>
    </div>
</div>