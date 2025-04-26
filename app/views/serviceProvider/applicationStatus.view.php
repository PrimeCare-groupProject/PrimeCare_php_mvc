<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/serviceprovider/serviceOverview?service_id=<?= $service->service_id ?>'><img src="<?= ROOT ?>/assets/images/backButton.png" alt="back" class="navigate-icons"></a>
    <h2>Application Status</h2>
</div>

<div class="application-status-container">
    <div class="status-card">
        <div class="service-info">
            <h3><?= htmlspecialchars($service->name) ?></h3>
            <p class="service-rate">LKR <?= number_format($service->cost_per_hour, 2) ?> per hour</p>
        </div>
        
        <div class="status-info">
            <div class="status-badge status-<?= strtolower($application->status) ?>">
                <?= $application->status ?>
            </div>
            
            <div class="application-details">
                <div class="detail-row">
                    <span class="label">Applied on:</span>
                    <span class="value"><?= date('F j, Y', strtotime($application->created_at)) ?></span>
                </div>
                
                <?php if (!empty($application->updated_at)): ?>
                <div class="detail-row">
                    <span class="label">Last updated:</span>
                    <span class="value"><?= date('F j, Y', strtotime($application->updated_at)) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($application->status === 'Approved'): ?>
                <div class="detail-row highlight">
                    <span class="label">Result:</span>
                    <span class="value">Your application was approved! You can now receive service requests for this service type.</span>
                </div>
                <?php elseif ($application->status === 'Rejected'): ?>
                <div class="detail-row highlight rejected">
                    <span class="label">Result:</span>
                    <span class="value">Your application was not approved at this time. You may apply again with improved qualifications.</span>
                </div>
                <?php else: ?>
                <div class="detail-row highlight pending">
                    <span class="label">Status:</span>
                    <span class="value">Your application is being reviewed by our team. We'll notify you once a decision is made.</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="proof-document">
            <h4>Your Submitted Document</h4>
            <div class="document-preview">
                <?php 
                $fileExtension = pathinfo($application->proof, PATHINFO_EXTENSION);
                if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png'])): 
                ?>
                    <img src="<?= ROOT ?>/<?= $application->proof ?>" alt="Proof Document">
                <?php else: ?>
                    <div class="pdf-preview">
                        <i class="fas fa-file-pdf"></i>
                        <a href="<?= ROOT ?>/<?= $application->proof ?>" target="_blank">View Document</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="action-buttons">
            <?php if ($application->status === 'Rejected'): ?>
            <a href="<?= ROOT ?>/serviceprovider/applyForService/<?= $service->service_id ?>" class="action-btn reapply">
                <i class="fas fa-redo"></i> Submit New Application
            </a>
            <?php endif; ?>
            
            <a href="<?= ROOT ?>/serviceprovider/serviceOverview?service_id=<?= $service->service_id ?>" class="action-btn back">
                <i class="fas fa-arrow-left"></i> Back to Service Details
            </a>
        </div>
    </div>
</div>

<style>
    .application-status-container {
        max-width: 800px;
        margin: 30px auto;
        padding: 0 20px;
    }
    
    .status-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        padding: 30px;
    }
    
    .service-info {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .service-info h3 {
        margin: 0 0 8px 0;
        color: #333;
        font-size: 24px;
    }
    
    .service-rate {
        color: #FFA000;
        font-weight: 500;
        margin: 0;
    }
    
    .status-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .status-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 20px;
    }
    
    .status-pending {
        background-color: #fff8e1;
        color: #FF8F00;
    }
    
    .status-approved {
        background-color: #e8f5e9;
        color: #388E3C;
    }
    
    .status-rejected {
        background-color: #ffebee;
        color: #d32f2f;
    }
    
    .application-details {
        width: 100%;
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
    }
    
    .detail-row {
        display: flex;
        margin: 10px 0;
    }
    
    .detail-row .label {
        width: 120px;
        font-weight: 500;
        color: #555;
    }
    
    .detail-row .value {
        flex: 1;
        color: #333;
    }
    
    .detail-row.highlight {
        background-color: #e8f5e9;
        padding: 10px;
        border-radius: 5px;
        margin-top: 15px;
    }
    
    .detail-row.highlight.rejected {
        background-color: #ffebee;
    }
    
    .detail-row.highlight.pending {
        background-color: #fff8e1;
    }
    
    .proof-document {
        margin: 20px 0;
    }
    
    .proof-document h4 {
        margin-top: 0;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .document-preview {
        padding: 15px;
        background-color: #f5f5f5;
        border-radius: 8px;
        text-align: center;
    }
    
    .document-preview img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 5px;
    }
    
    .pdf-preview {
        padding: 30px;
        border: 1px dashed #ccc;
        border-radius: 5px;
    }
    
    .pdf-preview i {
        font-size: 48px;
        color: #f44336;
        margin-bottom: 10px;
    }
    
    .pdf-preview a {
        display: block;
        color: #4e6ef7;
        font-weight: 500;
        text-decoration: none;
    }
    
    .action-buttons {
        margin-top: 30px;
        display: flex;
        justify-content: space-between;
        gap: 15px;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        flex: 1;
    }
    
    .action-btn i {
        margin-right: 8px;
    }
    
    .action-btn.reapply {
        background-color: #4CAF50;
        color: white;
    }
    
    .action-btn.back {
        background-color: #f5f5f5;
        color: #333;
    }
    
    .action-btn.reapply:hover {
        background-color: #388E3C;
    }
    
    .action-btn.back:hover {
        background-color: #e0e0e0;
    }
</style>

<?php require 'serviceproviderFooter.view.php' ?>