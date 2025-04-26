<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/agent/serviceManagement'><img src="<?= ROOT ?>/assets/images/backButton.png" alt="back" class="navigate-icons"></a>
    <h2>Service Provider Applications</h2>
    
    <div class="filter-container">
        <select id="statusFilter" onchange="filterApplications()">
            <option value="all" <?= $selected_status == 'all' ? 'selected' : '' ?>>All Applications</option>
            <option value="Pending" <?= $selected_status == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Approved" <?= $selected_status == 'Approved' ? 'selected' : '' ?>>Approved</option>
            <option value="Rejected" <?= $selected_status == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
    </div>
</div>

<!-- Flash Messages -->
<?php if (isset($_SESSION['flash']['msg'])): ?>
<div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
    <?= $_SESSION['flash']['msg'] ?>
    <span class="alert-close" onclick="this.parentElement.style.display='none'">&times;</span>
</div>
<?php unset($_SESSION['flash']); endif; ?>

<div class="applications-grid">
    <?php if (empty($applications)): ?>
        <div class="no-applications">
            <i class="fas fa-clipboard-list no-data-icon"></i>
            <p>No service applications found.</p>
            <?php if ($selected_status != 'all'): ?>
                <a href="<?= ROOT ?>/agent/serviceApplications?status=all" class="view-all-btn">
                    View All Applications
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php foreach ($applications as $application): ?>
            <div class="application-card" data-status="<?= $application->status ?>">
                <div class="card-header <?= strtolower($application->status) ?>">
                    <div class="status-badge">
                        <?= $application->status ?>
                    </div>
                    <div class="application-date">
                        Applied: <?= date('M j, Y', strtotime($application->created_at)) ?>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="service-info">
                        <h3><?= htmlspecialchars($application->service_name) ?></h3>
                        <p class="service-rate">LKR <?= number_format($application->cost_per_hour, 2) ?>/hr</p>
                    </div>
                    
                    <div class="applicant-info">
                        <div class="applicant-avatar">
                            <?php $initial = substr($application->provider_name, 0, 1); ?>
                            <div class="avatar-circle"><?= $initial ?></div>
                        </div>
                        <div class="applicant-details">
                            <p class="applicant-name"><?= htmlspecialchars($application->provider_name) ?></p>
                            <p class="applicant-contact"><?= htmlspecialchars($application->provider_contact) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <a href="<?= ROOT ?>/agent/viewApplicationDetails/<?= $application->service_id ?>/<?= $application->service_provider_id ?>" class="view-btn">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                    
                    <?php if ($application->status === 'Pending'): ?>
                    <div class="action-buttons">
                        <button class="approve-btn" onclick="approveApplication('<?= $application->service_id ?>', '<?= $application->service_provider_id ?>')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                        <button class="reject-btn" onclick="rejectApplication('<?= $application->service_id ?>', '<?= $application->service_provider_id ?>')">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination controls if needed -->
<?php if ($total_pages > 1): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="<?= ROOT ?>/agent/serviceApplications?page=<?= $i ?>&status=<?= $selected_status ?>" 
           class="<?= $i == $current_page ? 'active' : '' ?>">
           <?= $i ?>
        </a>
    <?php endfor; ?>
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

<style>
    /* Filter styles */
    .filter-container {
        margin-left: auto;
    }
    
    #statusFilter {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ddd;
        background-color: white;
        font-size: 14px;
        cursor: pointer;
    }
    
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
    
    /* Grid layout for application cards */
    .applications-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 25px;
        padding: 20px;
    }
    
    /* No applications message */
    .no-applications {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .no-data-icon {
        font-size: 48px;
        color: #ccc;
        margin-bottom: 15px;
    }
    
    .view-all-btn {
        margin-top: 15px;
        padding: 8px 16px;
        background-color: #f5f5f5;
        color: #333;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    
    .view-all-btn:hover {
        background-color: #e0e0e0;
    }
    
    /* Application card styles */
    .application-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .application-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }
    
    .card-header {
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }
    
    .card-header.pending {
        background-color: #fff8e1;
    }
    
    .card-header.approved {
        background-color: #e8f5e9;
    }
    
    .card-header.rejected {
        background-color: #ffebee;
    }
    
    .status-badge {
        font-weight: 600;
        font-size: 14px;
        padding: 4px 10px;
        border-radius: 20px;
    }
    
    .card-header.pending .status-badge {
        background-color: #fff176;
        color: #f57f17;
    }
    
    .card-header.approved .status-badge {
        background-color: #a5d6a7;
        color: #2e7d32;
    }
    
    .card-header.rejected .status-badge {
        background-color: #ef9a9a;
        color: #c62828;
    }
    
    .application-date {
        font-size: 13px;
        color: #666;
    }
    
    .card-body {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .service-info {
        margin-bottom: 15px;
    }
    
    .service-info h3 {
        margin: 0 0 5px;
        font-size: 18px;
        color: #333;
    }
    
    .service-rate {
        color: #f57c00;
        font-weight: 600;
        margin: 0;
        font-size: 15px;
    }
    
    .applicant-info {
        display: flex;
        align-items: center;
    }
    
    .applicant-avatar {
        margin-right: 12px;
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #4e6ef7;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        font-weight: 600;
    }
    
    .applicant-details {
        flex: 1;
    }
    
    .applicant-name {
        margin: 0 0 3px;
        font-size: 15px;
        font-weight: 500;
    }
    
    .applicant-contact {
        margin: 0;
        font-size: 13px;
        color: #666;
    }
    
    .card-footer {
        padding: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .view-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        background-color: #f5f5f5;
        color: #333;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s;
    }
    
    .view-btn:hover {
        background-color: #e0e0e0;
    }
    
    .view-btn i {
        margin-right: 6px;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
    }
    
    .approve-btn, .reject-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px 0;
        border: none;
        border-radius: 5px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .approve-btn {
        background-color: #4caf50;
        color: white;
    }
    
    .approve-btn:hover {
        background-color: #388e3c;
    }
    
    .reject-btn {
        background-color: #f44336;
        color: white;
    }
    
    .reject-btn:hover {
        background-color: #d32f2f;
    }
    
    .approve-btn i, .reject-btn i {
        margin-right: 6px;
    }
    
    /* Pagination styles */
    .pagination {
        display: flex;
        justify-content: center;
        margin: 30px 0;
    }
    
    .pagination a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        margin: 0 5px;
        border-radius: 50%;
        background-color: white;
        color: #333;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .pagination a:hover {
        background-color: #f5f5f5;
    }
    
    .pagination a.active {
        background-color: #4e6ef7;
        color: white;
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
</style>

<script>
    // Filter applications by status
    function filterApplications() {
        const status = document.getElementById('statusFilter').value;
        window.location.href = `<?= ROOT ?>/agent/serviceApplications?status=${status}`;
    }
    
    // Approval confirmation
    function approveApplication(serviceId, providerId) {
        const modal = document.getElementById('confirmationModal');
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');
        const confirmBtn = document.getElementById('confirm-btn');
        
        modalTitle.textContent = 'Approve Application';
        modalMessage.textContent = 'Are you sure you want to approve this service provider application?';
        
        confirmBtn.style.backgroundColor = '#4caf50';
        confirmBtn.onclick = function() {
            window.location.href = `<?= ROOT ?>/agent/updateApplicationStatus/${serviceId}/${providerId}/Approved`;
        };
        
        modal.classList.add('active');
    }
    
    // Rejection confirmation
    function rejectApplication(serviceId, providerId) {
        const modal = document.getElementById('confirmationModal');
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');
        const confirmBtn = document.getElementById('confirm-btn');
        
        modalTitle.textContent = 'Reject Application';
        modalMessage.textContent = 'Are you sure you want to reject this service provider application?';
        
        confirmBtn.style.backgroundColor = '#f44336';
        confirmBtn.onclick = function() {
            window.location.href = `<?= ROOT ?>/agent/updateApplicationStatus/${serviceId}/${providerId}/Rejected`;
        };
        
        modal.classList.add('active');
    }
    
    // Close modal
    function closeModal() {
        document.getElementById('confirmationModal').classList.remove('active');
    }
</script>

<?php require_once 'agentFooter.view.php'; ?>