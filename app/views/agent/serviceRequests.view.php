<?php require_once 'agentHeader.view.php'; ?>

<div class="service-requests-container">
    <h2>Service Request Management</h2>
    <p class="subtitle">Manage all property maintenance and service requests</p>
    
    <div class="service-cards">
        <div class="service-card">
            <div class="service-card-header">
                <i class="fa-solid fa-list-check"></i>
                <div class="service-count"><?= $tasksCount ?></div>
            </div>
            <div class="service-card-body">
                <h3>Tasks</h3>
                <p>Manage and track service tasks assigned to providers</p>
                <a href="<?= ROOT ?>/dashboard/tasks" class="service-btn">View Tasks</a>
            </div>
        </div>
        
        <div class="service-card">
            <div class="service-card-header">
                <i class="fa-solid fa-inbox"></i>
                <div class="service-count"><?= $pendingTasksCount ?></div>
            </div>
            <div class="service-card-body">
                <h3>Requested Tasks</h3>
                <p>Review and approve pending maintenance requests from property owners</p>
                <a href="<?= ROOT ?>/dashboard/requestedTasks" class="service-btn">View Requests</a>
            </div>
        </div>
        
        <div class="service-card">
            <div class="service-card-header">
                <i class="fa-solid fa-handshake-angle"></i>
                <div class="service-count"><?= $externalRequestsCount ?></div>
            </div>
            <div class="service-card-body">
                <h3>External Service Requests</h3>
                <p>Manage service requests that require external service providers</p>
                <a href="<?= ROOT ?>/dashboard/externalServiceRequests" class="service-btn">View External Requests</a>
            </div>
        </div>
    </div>

    <div class="service-overview">
        <div class="overview-header">
            <h3>Service Request Overview</h3>
        </div>
        <div class="overview-stats">
            <div class="stat-item">
                <div class="stat-value"><?= $pendingTasksCount + $externalRequestsCount ?></div>
                <div class="stat-label">Pending Requests</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $tasksCount ?></div>
                <div class="stat-label">Active Tasks</div>
            </div>
        </div>
    </div>
</div>

<style>
.service-requests-container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
}

.service-requests-container h2 {
    font-size: 28px;
    margin-bottom: 10px;
    color: #333;
}

.subtitle {
    color: #666;
    margin-bottom: 30px;
}

.service-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.service-card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
}

.service-card-header {
    background: linear-gradient(135deg, #4e6ef7, #6a5acd);
    padding: 25px;
    text-align: center;
    position: relative;
}

.service-card-header i {
    font-size: 2.5rem;
    color: white;
}

.service-count {
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: #ff5722;
    color: #fff;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.service-card-body {
    padding: 25px;
    text-align: center;
}

.service-card-body h3 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #333;
}

.service-card-body p {
    color: #666;
    margin-bottom: 20px;
    min-height: 60px;
    line-height: 1.5;
}

.service-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4e6ef7;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 500;
    transition: background-color 0.3s;
}

.service-btn:hover {
    background-color: #3a5ae8;
}

.service-overview {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    margin-top: 20px;
    overflow: hidden;
}

.overview-header {
    border-bottom: 1px solid #eaeaea;
    padding: 20px 25px;
}

.overview-header h3 {
    font-size: 18px;
    margin: 0;
    color: #333;
}

.overview-stats {
    display: flex;
    padding: 20px;
}

.stat-item {
    flex: 1;
    text-align: center;
    padding: 15px;
}

.stat-value {
    font-size: 32px;
    font-weight: bold;
    color: #4e6ef7;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    color: #666;
}

@media (max-width: 768px) {
    .service-cards {
        grid-template-columns: 1fr;
    }
    
    .overview-stats {
        flex-direction: column;
    }
    
    .stat-item {
        margin-bottom: 15px;
    }
}
</style>

<?php require_once 'agentFooter.view.php'; ?>