<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/serviceApplications'><img src="<?= ROOT ?>/assets/images/backButton.png" alt="back" class="navigate-icons"></a>
    <h2>Application Details</h2>
    
    <div class="status-tag <?= strtolower($application->status) ?>">
        <?= $application->status ?>
    </div>
</div>

<!-- Flash Messages - Improved positioning and visibility -->
<?php if (isset($_SESSION['flash']['msg'])): ?>
<div id="flash-message" class="alert alert-<?= $_SESSION['flash']['type'] ?>">
    <div class="alert-content">
        <i class="fas fa-<?= $_SESSION['flash']['type'] === 'success' ? 'check-circle' : ($_SESSION['flash']['type'] === 'error' ? 'exclamation-circle' : 'info-circle') ?>"></i>
        <span><?= $_SESSION['flash']['msg'] ?></span>
    </div>
    <span class="alert-close" onclick="closeFlashMessage()">&times;</span>
</div>
<script>
    // Auto-hide flash message after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            closeFlashMessage();
        }, 5000);
    });
    
    function closeFlashMessage() {
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            flashMessage.classList.add('fade-out');
            setTimeout(function() {
                flashMessage.style.display = 'none';
            }, 300);
        }
    }
</script>
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
                <div class="image-container">
                    <img src="<?= ROOT ?>/<?= $application->proof ?>" alt="Proof Document" class="proof-image">
                    <button class="view-document-btn" onclick="openDocumentViewer('<?= ROOT ?>/<?= $application->proof ?>', 'image')">
                        <i class="fas fa-search-plus"></i> Examine Document
                    </button>
                </div>
            <?php elseif ($file_extension === 'pdf'): ?>
                <div class="pdf-container">
                    <i class="fas fa-file-pdf pdf-icon"></i>
                    <p>PDF Document</p>
                    <button class="view-document-btn" onclick="openDocumentViewer('<?= ROOT ?>/<?= $application->proof ?>', 'pdf')">
                        <i class="fas fa-search-plus"></i> Examine Document
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
        <!-- Changed to direct links instead of confirmation popup -->
        <a href="<?= ROOT ?>/dashboard/updateApplicationStatus/<?= $application->service_id ?>/<?= $application->service_provider_id ?>/Rejected" class="reject-btn">
            <i class="fas fa-times-circle"></i> Reject Application
        </a>
        <a href="<?= ROOT ?>/dashboard/updateApplicationStatus/<?= $application->service_id ?>/<?= $application->service_provider_id ?>/Approved" class="approve-btn">
            <i class="fas fa-check-circle"></i> Approve Application
        </a>
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
            <!-- Changed to direct link without confirmation -->
            <a href="<?= ROOT ?>/dashboard/updateApplicationStatus/<?= $application->service_id ?>/<?= $application->service_provider_id ?>/Pending" class="change-decision-btn">
                <i class="fas fa-sync"></i> Change Decision
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Keep the document viewer modal section -->
<div id="documentViewerModal" class="modal document-modal">
    <div class="document-modal-content">
        <div class="document-modal-header">
            <h3>Proof Document</h3>
            <div class="document-controls">
                <button id="zoomInBtn" class="control-btn" title="Zoom In" onclick="zoomIn()">
                    <i class="fas fa-search-plus"></i>
                </button>
                <button id="zoomOutBtn" class="control-btn" title="Zoom Out" onclick="zoomOut()">
                    <i class="fas fa-search-minus"></i>
                </button>
                <button id="resetZoomBtn" class="control-btn" title="Reset View" onclick="resetZoom()">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button id="fullscreenBtn" class="control-btn" title="Toggle Fullscreen" onclick="toggleFullscreen()">
                    <i class="fas fa-expand"></i>
                </button>
                <button class="document-close-btn" onclick="closeDocumentViewer()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="document-modal-body">
            <!-- For PDFs -->
            <div id="pdfContainer" class="document-container">
                <iframe id="pdfFrame" src="" frameborder="0"></iframe>
            </div>
            
            <!-- For Images -->
            <div id="imageContainer" class="document-container">
                <div id="imageWrapper">
                    <img id="documentImage" src="" alt="Proof Document">
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Updated Alert styles for overlay positioning */
.alert {
    padding: 15px 20px;
    margin: 0;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: calc(100% - 40px);
    max-width: 800px;
    box-sizing: border-box;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    position: fixed; /* Changed from relative to fixed */
    top: 20px; /* Position from top */
    left: 50%; /* Center horizontally */
    transform: translateX(-50%); /* Center adjustment */
    z-index: 9999; /* Ensure it's above everything */
    animation: slideDown 0.4s ease-out;
}

    .alert-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-content i {
        font-size: 20px;
    }
    
    .alert-success {
        background-color: #e8f5e9;
        color: #2e7d32;
        border-left: 5px solid #4caf50;
    }
    
    .alert-error {
        background-color: #ffebee;
        color: #c62828;
        border-left: 5px solid #f44336;
    }
    
    .alert-warning {
        background-color: #fff8e1;
        color: #f57c00;
        border-left: 5px solid #ffc107;
    }
    
    .alert-close {
        cursor: pointer;
        font-size: 22px;
        font-weight: bold;
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    
    .alert-close:hover {
        opacity: 1;
    }
    
    .fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }
    
    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
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
    
    .view-document-btn,
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
    
    .view-document-btn:hover,
    .download-btn:hover {
        background-color: #e0e0e0;
    }
    
    .view-document-btn i,
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
        text-decoration: none;
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
    
    /* Modal styles update - replace the existing modal CSS */
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    overflow: hidden;
}

.modal.active {
    display: flex !important; /* Changed from block to flex */
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: white;
    border-radius: 10px;
    padding: 30px;
    width: 450px;
    max-width: 90%;
    box-shadow: 0 5px 25px rgba(0,0,0,0.3);
    position: relative;
    animation: modalAppear 0.3s ease;
}

/* Document viewer modal specific styles */
.document-modal.active {
    display: flex !important;
    align-items: center;
    justify-content: center;
}

.document-modal-content {
    background-color: white;
    width: 90%;
    height: 90%;
    max-width: 1200px;
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 5px 25px rgba(0,0,0,0.3);
    animation: modalAppear 0.3s ease;
}

.document-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
}

.document-modal-header h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
}

.document-controls {
    display: flex;
    gap: 10px;
    align-items: center;
}

.control-btn, 
.document-close-btn {
    background: none;
    border: none;
    font-size: 16px;
    padding: 5px;
    border-radius: 4px;
    cursor: pointer;
    color: #555;
    transition: all 0.2s;
}

.control-btn:hover,
.document-close-btn:hover {
    background-color: #e0e0e0;
    color: #333;
}

.document-modal-body {
    flex: 1;
    overflow: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #333;
    position: relative;
}

/* Container for document types */
.document-container {
    width: 100%;
    height: 100%;
    display: none;
}

#pdfContainer {
    width: 100%;
    height: 100%;
    overflow: auto;
}

#pdfFrame {
    width: 100%;
    height: 100%;
    border: none;
}

#imageContainer {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    overflow: auto;
    position: relative;
}

#imageWrapper {
    position: relative;
    display: inline-block;
    transition: transform 0.3s ease;
}

#documentImage {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

/* Zoom classes for PDF */
.zoom-50 {
    transform: scale(0.5);
    transform-origin: top left;
}

.zoom-75 {
    transform: scale(0.75);
    transform-origin: top left;
}

.zoom-100 {
    transform: scale(1);
    transform-origin: top left;
}

.zoom-125 {
    transform: scale(1.25);
    transform-origin: top left;
}

.zoom-150 {
    transform: scale(1.5);
    transform-origin: top left;
}

.zoom-175 {
    transform: scale(1.75);
    transform-origin: top left;
}

.zoom-200 {
    transform: scale(2);
    transform-origin: top left;
}

.zoom-250 {
    transform: scale(2.5);
    transform-origin: top left;
}

.zoom-300 {
    transform: scale(3);
    transform-origin: top left;
}

@keyframes modalAppear {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Button styles for confirmation modal */
.close-modal {
    position: absolute;
    right: 20px;
    top: 15px;
    background: none;
    border: none;
    font-size: 22px;
    color: #888;
    cursor: pointer;
}

.close-modal:hover {
    color: #333;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 25px;
}

#cancel-btn, 
#confirm-btn {
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

#cancel-btn {
    background-color: #f5f5f5;
    color: #333;
}

#cancel-btn:hover {
    background-color: #e0e0e0;
}

#confirm-btn {
    background-color: #4e6ef7;
    color: white;
}

#confirm-btn:hover {
    filter: brightness(1.1);
}

#confirm-btn.confirm-approve {
    background-color: #4caf50;
}

#confirm-btn.confirm-reject {
    background-color: #f44336;
}
</style>

<script>
    // Updated document viewer functions
    let currentScale = 1;
    let isFullscreen = false;
    
    function openDocumentViewer(docUrl, type) {
        // Reset zoom
        currentScale = 1;
        
        if (type === 'pdf') {
            // For PDFs
            const pdfFrame = document.getElementById('pdfFrame');
            const pdfContainer = document.getElementById('pdfContainer');
            
            // Clear any zoom classes first
            pdfFrame.className = 'zoom-100';
            
            // Show PDF container
            pdfContainer.style.display = 'block';
            document.getElementById('imageContainer').style.display = 'none';
            
            // Set source - adding #view=FitH at the end makes the PDF fit the width
            pdfFrame.src = docUrl + '#view=FitH';
            
            // Enable scrolling in the container
            pdfContainer.style.overflow = 'auto';
        } else if (type === 'image') {
            // For Images
            document.getElementById('documentImage').src = docUrl;
            document.getElementById('imageContainer').style.display = 'flex';
            document.getElementById('pdfContainer').style.display = 'none';
            
            // Reset image transform
            document.getElementById('documentImage').style.transform = 'scale(1)';
        }
        
        // Show the document modal
        document.getElementById('documentViewerModal').classList.add('active');
        
        // Immediately go into fullscreen mode for better visibility
        setTimeout(() => {
            if (!isFullscreen) toggleFullscreen();
        }, 200);
        
        // Prevent body scrolling when modal is open
        document.body.style.overflow = 'hidden';
        
        // Add ESC key listener
        document.addEventListener('keydown', handleEscKey);
    }
    
    function closeDocumentViewer() {
        // Hide the document modal
        document.getElementById('documentViewerModal').classList.remove('active');
        
        // Exit fullscreen if active
        if (isFullscreen) {
            toggleFullscreen();
        }
        
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
            closeDocumentViewer();
        }
    }
    
    function zoomIn() {
        if (currentScale < 3) {
            currentScale += 0.25;
            updateZoom();
        }
    }
    
    function zoomOut() {
        if (currentScale > 0.5) {
            currentScale -= 0.25;
            updateZoom();
        }
    }
    
    function resetZoom() {
        currentScale = 1;
        updateZoom();
    }
    
    function updateZoom() {
        // Get currently visible container
        const pdfVisible = document.getElementById('pdfContainer').style.display === 'block';
        
        if (pdfVisible) {
            // For PDFs: Use classes to apply zoom
            const pdfFrame = document.getElementById('pdfFrame');
            
            // Clear existing zoom classes
            pdfFrame.className = '';
            
            // Map currentScale to the nearest zoom class
            let zoomClass = 'zoom-100'; // default
            
            if (currentScale <= 0.5) zoomClass = 'zoom-50';
            else if (currentScale <= 0.75) zoomClass = 'zoom-75';
            else if (currentScale <= 1) zoomClass = 'zoom-100';
            else if (currentScale <= 1.25) zoomClass = 'zoom-125';
            else if (currentScale <= 1.5) zoomClass = 'zoom-150';
            else if (currentScale <= 1.75) zoomClass = 'zoom-175';
            else if (currentScale <= 2) zoomClass = 'zoom-200';
            else if (currentScale <= 2.5) zoomClass = 'zoom-250';
            else zoomClass = 'zoom-300';
            
            pdfFrame.classList.add(zoomClass);
            
        } else {
            // For images: Use transform
            const image = document.getElementById('documentImage');
            if (image) {
                image.style.transform = `scale(${currentScale})`;
            }
        }
        
        // Update zoom percentage display if you have one
        // document.getElementById('zoomPercentage').textContent = `${Math.round(currentScale * 100)}%`;
    }
    
    function toggleFullscreen() {
        const modal = document.getElementById('documentViewerModal');
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        
        if (!isFullscreen) {
            // Enter fullscreen
            if (modal.requestFullscreen) {
                modal.requestFullscreen();
            } else if (modal.webkitRequestFullscreen) {
                modal.webkitRequestFullscreen();
            } else if (modal.msRequestFullscreen) {
                modal.msRequestFullscreen();
            }
            
            fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
            isFullscreen = true;
        } else {
            // Exit fullscreen
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            
            fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
            isFullscreen = false;
        }
    }
    
    // Add click outside to close
    document.getElementById('documentViewerModal').addEventListener('click', function(e) {
        // Only close if the click is directly on the modal background, not on its content
        if (e.target === this) {
            closeDocumentViewer();
        }
    });
</script>

<?php require_once 'agentFooter.view.php'; ?>