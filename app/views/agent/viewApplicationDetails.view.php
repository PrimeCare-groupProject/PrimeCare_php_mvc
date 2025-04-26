<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/agent/serviceApplications'><img src="<?= ROOT ?>/assets/images/backButton.png" alt="back" class="navigate-icons"></a>
    <h2>Application Details</h2>
    
    <div class="status-tag <?= strtolower($application->status) ?>">
        <?= $application->status ?>
    </div>
</div>

<!-- Flash Messages -->
<?php if (isset($_SESSION['flash']['msg'])): ?>
<div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
    <?= $_SESSION['flash']['msg'] ?>
    <span class="alert-close" onclick="this.parentElement.style.display='none'">&times;</span>
</div>
<?php unset($_SESSION['flash']); endif; ?>

<div class="application-details-container">
    <div class="application-info-card">
        <div class="info-section">
            <h3>Service Details</h3>
            <div class="info-grid">
                <div class="info-row">
                    <span class="label">Service Name:</span>
                    <span class="value"><?= htmlspecialchars($service->name) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Rate:</span>
                    <span class="value">LKR <?= number_format($service->cost_per_hour, 2) ?> per hour</span>
                </div>
                <div class="info-row">
                    <span class="label">Description:</span>
                    <span class="value"><?= htmlspecialchars($service->description) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Application Date:</span>
                    <span class="value"><?= date('F j, Y', strtotime($application->created_at)) ?></span>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <h3>Applicant Details</h3>
            <div class="info-grid">
                <div class="info-row">
                    <span class="label">Full Name:</span>
                    <span class="value"><?= htmlspecialchars($provider->fname . ' ' . $provider->lname) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value"><?= htmlspecialchars($provider->email) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Contact Number:</span>
                    <span class="value"><?= htmlspecialchars($provider->contact) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Joined:</span>
                    <span class="value"><?= isset($provider->created_at) ? date('F j, Y', strtotime($provider->created_at)) : 'N/A' ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="proof-document-card">
        <h3>Proof Document</h3>
        <div class="document-preview">
            <?php 
            $file_extension = strtolower(pathinfo($application->proof, PATHINFO_EXTENSION));
            if (in_array($file_extension, ['jpg', 'jpeg', 'png'])): 
            ?>
                <img src="<?= ROOT ?>/<?= $application->proof ?>" alt="Proof Document" class="proof-image">
            <?php elseif ($file_extension === 'pdf'): ?>
                <div class="pdf-container">
                    <i class="fas fa-file-pdf pdf-icon"></i>
                    <p>PDF Document</p>
                    <button class="view-pdf-btn" onclick="openPdfViewer('<?= ROOT ?>/<?= $application->proof ?>')">
                        <i class="fas fa-search-plus"></i> View PDF
                    </button>
                </div>
            <?php else: ?>
                <div class="unsupported-file">
                    <i class="fas fa-file-alt"></i>
                    <p>Unsupported File Format</p>
                    <a href="<?= ROOT ?>/<?= $application->proof ?>" target="_blank" class="download-btn">
                        <i class="fas fa-download"></i> Download File
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($application->status === 'Pending'): ?>
<div class="action-container">
    <h3>Application Decision</h3>
    <div class="decision-buttons">
        <button class="reject-btn" onclick="rejectApplication()">
            <i class="fas fa-times-circle"></i> Reject Application
        </button>
        <button class="approve-btn" onclick="approveApplication()">
            <i class="fas fa-check-circle"></i> Approve Application
        </button>
    </div>
</div>
<?php endif; ?>

<?php if ($application->status !== 'Pending'): ?>
<div class="decision-info">
    <div class="decision-header <?= strtolower($application->status) ?>">
        <i class="fas fa-<?= $application->status === 'Approved' ? 'check-circle' : 'times-circle' ?>"></i>
        <h3>Application <?= $application->status ?></h3>
    </div>
    
    <div class="decision-details">
        <p>
            This application was <?= strtolower($application->status) ?> 
            <?= !empty($application->updated_at) ? 'on ' . date('F j, Y', strtotime($application->updated_at)) : '' ?>.
        </p>
        
        <?php if ($application->status === 'Approved'): ?>
        <p class="approval-note">
            The service provider has been granted permission to provide this service to customers.
        </p>
        <?php elseif ($application->status === 'Rejected'): ?>
        <p class="rejection-note">
            The service provider cannot provide this service to customers.
        </p>
        <?php endif; ?>
        
        <div class="decision-actions">
            <button class="change-decision-btn" onclick="confirmChangeDecision()">
                <i class="fas fa-sync"></i> Change Decision
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h3 id="modal-title">Confirm Action</h3>
        <p id="modal-message"></p>
        <div class="modal-actions">
            <button id="cancel-btn" onclick="closeModal()">Cancel</button>
            <button id="confirm-btn">Confirm</button>
        </div>
    </div>
</div>

<!-- PDF Viewer Modal -->
<div id="pdfViewerModal" class="modal pdf-modal">
    <div class="pdf-modal-content">
        <div class="pdf-modal-header">
            <h3>Proof Document</h3>
            <button class="pdf-close-btn" onclick="closePdfViewer()">&times;</button>
        </div>
        <div class="pdf-modal-body">
            <iframe id="pdfFrame" src="" width="100%" height="100%" frameborder="0"></iframe>
        </div>
    </div>
</div>

<style>
    /* Alert styles */
    .alert {
        padding: 12px 20px;
        margin: 20px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .alert-success {
        background-color: #e8f5e9;
        color: #388e3c;
        border-left: 4px solid #4caf50;
    }
    
    .alert-error {
        background-color: #ffebee;
        color: #d32f2f;
        border-left: 4px solid #f44336;
    }
    
    .alert-warning {
        background-color: #fff8e1;
        color: #f57c00;
        border-left: 4px solid #ffc107;
    }
    
    .alert-close {
        cursor: pointer;
        font-size: 20px;
        font-weight: bold;
    }
    
    /* Status tag in menu bar */
    .status-tag {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
        margin-left: auto;
    }
    
    .status-tag.pending {
        background-color: #fff8e1;
        color: #f57f17;
    }
    
    .status-tag.approved {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    
    .status-tag.rejected {
        background-color: #ffebee;
        color: #c62828;
    }
    
    /* Layout */
    .application-details-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        padding: 20px;
    }
    
    .application-info-card, 
    .proof-document-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    /* Info section styles */
    .info-section {
        padding: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .info-section:last-child {
        border-bottom: none;
    }
    
    .info-section h3 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 18px;
        color: #333;
    }
    
    .info-grid {
        display: grid;
        gap: 12px;
    }
    
    .info-row {
        display: grid;
        grid-template-columns: 140px 1fr;
        gap: 15px;
    }
    
    .info-row .label {
        color: #666;
        font-weight: 500;
    }
    
    .info-row .value {
        color: #333;
    }
    
    /* Proof document styles */
    .proof-document-card {
        padding: 20px;
        display: flex;
        flex-direction: column;
    }
    
    .proof-document-card h3 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 18px;
        color: #333;
    }
    
    .document-preview {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 8px;
        background-color: #f9f9f9;
    }
    
    .proof-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .pdf-container,
    .unsupported-file {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
        text-align: center;
    }
    
    .pdf-icon,
    .unsupported-file i {
        font-size: 48px;
        color: #e53935;
        margin-bottom: 15px;
    }
    
    .unsupported-file i {
        color: #757575;
    }
    
    .pdf-container p,
    .unsupported-file p {
        margin: 10px 0 20px;
        color: #666;
    }
    
    .view-pdf-btn,
    .download-btn {
        display: inline-flex;
        align-items: center;
        background-color: #f5f5f5;
        color: #333;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s;
    }
    
    .view-pdf-btn:hover,
    .download-btn:hover {
        background-color: #e0e0e0;
    }
    
    .view-pdf-btn i,
    .download-btn i {
        margin-right: 8px;
    }
    
    /* Action container styles */
    .action-container {
        padding: 20px;
        margin: 0 20px 20px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .action-container h3 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 18px;
        color: #333;
    }
    
    .decision-buttons {
        display: flex;
        gap: 15px;
    }
    
    .approve-btn,
    .reject-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .approve-btn {
        background-color: #4caf50;
        color: white;
    }
    
    .reject-btn {
        background-color: #f44336;
        color: white;
    }
    
    .approve-btn:hover {
        background-color: #388e3c;
    }
    
    .reject-btn:hover {
        background-color: #d32f2f;
    }
    
    .approve-btn i,
    .reject-btn i {
        margin-right: 8px;
        font-size: 18px;
    }
    
    /* Decision info styles */
    .decision-info {
        padding: 20px;
        margin: 0 20px 20px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .decision-header {
        display: flex;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
        margin-bottom: 15px;
    }
    
    .decision-header.approved {
        color: #2e7d32;
    }
    
    .decision-header.rejected {
        color: #c62828;
    }
    
    .decision-header i {
        font-size: 28px;
        margin-right: 12px;
    }
    
    .decision-header h3 {
        margin: 0;
        font-size: 18px;
    }
    
    .decision-details p {
        margin: 10px 0;
        line-height: 1.5;
    }
    
    .approval-note {
        color: #2e7d32;
    }
    
    .rejection-note {
        color: #c62828;
    }
    
    .decision-actions {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
    }
    
    .change-decision-btn {
        display: flex;
        align-items: center;
        background-color: #f5f5f5;
        color: #333;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .change-decision-btn:hover {
        background-color: #e0e0e0;
    }
    
    .change-decision-btn i {
        margin-right: 8px;
    }
    
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }
    
    .modal.active {
        display: flex;
    }
    
    .modal-content {
        background-color: white;
        border-radius: 10px;
        padding: 25px;
        width: 400px;
        max-width: 90%;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        position: relative;
    }
    
    .close-modal {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 24px;
        color: #999;
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .close-modal:hover {
        color: #333;
    }
    
    #modal-title {
        margin-top: 0;
        margin-bottom: 15px;
    }
    
    #modal-message {
        margin-bottom: 25px;
    }
    
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    
    #cancel-btn, #confirm-btn {
        padding: 8px 20px;
        border-radius: 5px;
        font-weight: 500;
        cursor: pointer;
        border: none;
    }
    
    #cancel-btn {
        background-color: #f5f5f5;
        color: #333;
    }
    
    #confirm-btn {
        background-color: #4e6ef7;
        color: white;
    }
    
    #cancel-btn:hover {
        background-color: #e0e0e0;
    }
    
    #confirm-btn:hover {
        background-color: #3a56cc;
    }
    
    /* PDF Modal styles */
.pdf-modal .pdf-modal-content {
    width: 90%;
    height: 90%;
    max-width: 1200px;
    display: flex;
    flex-direction: column;
    padding: 0;
    overflow: hidden;
}

.pdf-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
    background-color: #f8f8f8;
    border-bottom: 1px solid #ddd;
}

.pdf-modal-header h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
}

.pdf-close-btn {
    background: none;
    border: none;
    font-size: 24px;
    color: #666;
    cursor: pointer;
    transition: color 0.2s;
}

.pdf-close-btn:hover {
    color: #333;
}

.pdf-modal-body {
    flex: 1;
    overflow: hidden;
    background-color: #525659;
}

#pdfFrame {
    width: 100%;
    height: 100%;
    border: none;
}

/* Mobile optimization */
@media (max-width: 768px) {
    .pdf-modal .pdf-modal-content {
        width: 95%;
        height: 95%;
    }
    
    .pdf-modal-header {
        padding: 10px 15px;
    }
}
</style>

<script>
    // Approval confirmation
    function approveApplication() {
        const modal = document.getElementById('confirmationModal');
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');
        const confirmBtn = document.getElementById('confirm-btn');
        
        modalTitle.textContent = 'Approve Application';
        modalMessage.textContent = 'Are you sure you want to approve this service provider application?';
        
        confirmBtn.style.backgroundColor = '#4caf50';
        confirmBtn.onclick = function() {
            window.location.href = '<?= ROOT ?>/agent/updateApplicationStatus/<?= $application->service_id ?>/<?= $application->service_provider_id ?>/Approved';
        };
        
        modal.classList.add('active');
    }
    
    // Rejection confirmation
    function rejectApplication() {
        const modal = document.getElementById('confirmationModal');
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');
        const confirmBtn = document.getElementById('confirm-btn');
        
        modalTitle.textContent = 'Reject Application';
        modalMessage.textContent = 'Are you sure you want to reject this service provider application?';
        
        confirmBtn.style.backgroundColor = '#f44336';
        confirmBtn.onclick = function() {
            window.location.href = '<?= ROOT ?>/agent/updateApplicationStatus/<?= $application->service_id ?>/<?= $application->service_provider_id ?>/Rejected';
        };
        
        modal.classList.add('active');
    }
    
    // Change decision confirmation
    function confirmChangeDecision() {
        const modal = document.getElementById('confirmationModal');
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');
        const confirmBtn = document.getElementById('confirm-btn');
        
        modalTitle.textContent = 'Change Decision';
        modalMessage.textContent = 'Are you sure you want to change your decision for this application?';
        
        confirmBtn.style.backgroundColor = '#FF9800';
        confirmBtn.onclick = function() {
            window.location.href = '<?= ROOT ?>/agent/updateApplicationStatus/<?= $application->service_id ?>/<?= $application->service_provider_id ?>/Pending';
        };
        
        modal.classList.add('active');
    }
    
    // Close modal
    function closeModal() {
        document.getElementById('confirmationModal').classList.remove('active');
    }
    
    // PDF viewer functions
function openPdfViewer(pdfUrl) {
    // Set the iframe source to the PDF
    document.getElementById('pdfFrame').src = pdfUrl;
    
    // Show the PDF modal
    document.getElementById('pdfViewerModal').classList.add('active');
    
    // Prevent body scrolling when modal is open
    document.body.style.overflow = 'hidden';
    
    // Add ESC key listener
    document.addEventListener('keydown', handleEscKey);
}

function closePdfViewer() {
    // Hide the PDF modal
    document.getElementById('pdfViewerModal').classList.remove('active');
    
    // Re-enable body scrolling
    document.body.style.overflow = '';
    
    // Clean up iframe src to stop PDF loading/rendering
    setTimeout(() => {
        document.getElementById('pdfFrame').src = '';
    }, 300);
    
    // Remove ESC key listener
    document.removeEventListener('keydown', handleEscKey);
}

function handleEscKey(event) {
    if (event.key === 'Escape') {
        closePdfViewer();
    }
}

// Add click outside to close
document.getElementById('pdfViewerModal').addEventListener('click', function(e) {
    // Only close if the click is directly on the modal background, not on its content
    if (e.target === this) {
        closePdfViewer();
    }
});
</script>

<?php require_once 'agentFooter.view.php'; ?>