<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/serviceprovider/repairListing'><img src="<?= ROOT ?>/assets/images/backButton.png" alt="back" class="navigate-icons"></a>
    <h2>Service Details</h2>
</div>

<div class="service-overview-container">
    <!-- Service Details Section -->
    <div class="service-details-card">
        <div class="service-header">
            <h2><?= htmlspecialchars($service->name) ?></h2>
            <div class="service-rate">LKR <?= number_format($service->cost_per_hour, 2) ?> per hour</div>
        </div>
        
        <div class="service-content">
            <?php if (!empty($service->service_img)): ?>
                <div class="service-image-container">
                    <img src="<?= ROOT ?>/<?= $service->service_img ?>" alt="<?= htmlspecialchars($service->name) ?>">
                </div>
            <?php endif; ?>
            
            <div class="service-info">
                <h3>Description</h3>
                <p><?= htmlspecialchars($service->description) ?></p>
                
                <div class="service-stats">
                    <div class="stat-item">
                        <span class="stat-label">Service ID</span>
                        <span class="stat-value"><?= $service->service_id ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Hourly Rate</span>
                        <span class="stat-value">LKR <?= number_format($service->cost_per_hour, 2) ?></span>
                    </div>
                </div>
                
                <!-- <div class="service-action">
                    <button class="request-service-btn" onclick="requestService(<?= $service->service_id ?>)">
                        <i class="fas fa-tools"></i> Request New Service
                    </button>
                </div> -->
            </div>
        </div>
    </div>
    
    <!-- Previous Tasks Section -->
    <div class="previous-tasks-section">
        <h3><i class="fas fa-history"></i> Your Previous Tasks</h3>
        
        <?php if (empty($previous_tasks)): ?>
            <div class="no-tasks">
                <p>You haven't completed any tasks in this service category yet.</p>
            </div>
        <?php else: ?>
            <div class="tasks-container">
                <?php foreach ($previous_tasks as $task): ?>
                    <div class="task-card">
                        <div class="task-header">
                            <h4><?= htmlspecialchars($task->property_name) ?></h4>
                            <span class="task-date"><?= date('M d, Y', strtotime($task->date)) ?></span>
                        </div>
                        <div class="task-details">
                            <div class="task-info">
                                <span><i class="fas fa-clock"></i> <?= $task->total_hours ?> hours</span>
                                <span><i class="fas fa-money-bill-wave"></i> LKR <?= number_format($task->cost_per_hour * $task->total_hours, 2) ?></span>
                            </div>
                            <?php if (!empty($task->service_provider_description)): ?>
                                <p class="task-description"><?= htmlspecialchars($task->service_provider_description) ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($task->service_images)): ?>
                                <?php $images = json_decode($task->service_images); ?>
                                <?php if (!empty($images)): ?>
                                    <div class="task-images">
                                        <?php foreach ($images as $image): ?>
                                            <div class="task-image">
                                                <img src="<?= ROOT ?>/assets/images/uploads/service_logs/<?= $image ?>" alt="Service Image">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <a href="<?= ROOT ?>/serviceprovider/serviceSummery?service_id=<?= $task->service_id ?>" class="view-details-btn">
                                View Details
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .service-overview-container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .service-details-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 25px;
        overflow: hidden;
    }
    
    .service-header {
        padding: 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f9f9f9;
    }
    
    .service-header h2 {
        margin: 0;
        color: #333;
        font-size: 22px;
    }
    
    .service-rate {
        background-color:  #FFA000;
        color: white;
        padding: 8px 12px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 14px;
    }
    
    .service-content {
        display: flex;
        padding: 20px;
    }
    
    .service-image-container {
        flex: 0 0 300px;
        margin-right: 20px;
    }
    
    .service-image-container img {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .service-info {
        flex: 1;
    }
    
    .service-info h3 {
        margin-top: 0;
        color: #333;
        font-size: 18px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    
    .service-info p {
        color: #555;
        line-height: 1.6;
        font-size: 16px;
    }
    
    .service-stats {
        display: flex;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    
    .stat-item {
        flex: 1;
        background: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        margin-right: 10px;
        margin-bottom: 10px;
        min-width: 150px;
    }
    
    .stat-label {
        display: block;
        color: #777;
        font-size: 13px;
        margin-bottom: 5px;
    }
    
    .stat-value {
        display: block;
        color: #333;
        font-weight: bold;
        font-size: 16px;
    }
    
    .service-action {
        margin-top: 25px;
    }
    
    .request-service-btn {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 16px;
        transition: background-color 0.3s;
        display: inline-flex;
        align-items: center;
    }
    
    .request-service-btn i {
        margin-right: 8px;
    }
    
    .request-service-btn:hover {
        background-color: #388E3C;
    }
    
    /* Previous Tasks Section */
    .previous-tasks-section {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .previous-tasks-section h3 {
        color: #333;
        font-size: 18px;
        margin-top: 0;
        margin-bottom: 20px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }
    
    .previous-tasks-section h3 i {
        margin-right: 10px;
        color:  #FFA000;
    }
    
    .tasks-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .task-card {
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .task-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .task-header {
        background-color:  #FFA000;
        color: white;
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .task-header h4 {
        margin: 0;
        font-size: 16px;
    }
    
    .task-date {
        font-size: 12px;
        background-color: rgba(255,255,255,0.2);
        padding: 3px 8px;
        border-radius: 12px;
    }
    
    .task-details {
        padding: 15px;
    }
    
    .task-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
        color: #555;
    }
    
    .task-description {
        font-size: 14px;
        color: #555;
        margin: 10px 0;
        line-height: 1.5;
    }
    
    .task-images {
        display: flex;
        gap: 5px;
        overflow-x: auto;
        padding: 5px 0;
        margin: 10px 0;
    }
    
    .task-image {
        flex: 0 0 80px;
        height: 80px;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .task-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .view-details-btn {
        display: inline-block;
        background-color:  #FFA000;
        color: white;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 14px;
        margin-top: 10px;
        transition: background-color 0.3s;
    }
    
    .view-details-btn:hover {
        background-color: #1976D2;
    }
    
    .no-tasks {
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        color: #777;
        font-style: italic;
    }
    
    /* Media queries for responsiveness */
    @media (max-width: 768px) {
        .service-content {
            flex-direction: column;
        }
        
        .service-image-container {
            margin-right: 0;
            margin-bottom: 20px;
            flex: 0 0 auto;
        }
        
        .tasks-container {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }
</style>

<script>
    function requestService(serviceId) {
        window.location.href = '<?= ROOT ?>/dashboard/propertylisting/servicerequest?service_id=' + serviceId;
    }
</script>

<?php require 'serviceproviderFooter.view.php' ?>