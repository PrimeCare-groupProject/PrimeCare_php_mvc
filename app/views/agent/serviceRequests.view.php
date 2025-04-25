<?php require_once 'agentHeader.view.php'; ?>

<!-- Updated header to match management style -->
<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Service Request Management</h2>
</div>

<div class="MM__cards_container">
        <a href="<?= ROOT ?>/dashboard/tasks">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Tasks</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/serv1.jpg" alt="Employee Management" class="MM__img">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/requestedTasks">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Requested Tasks</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/serv2.jpg" alt="Property Management" class="MM__img">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/externalServiceRequests">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>External Service Requests</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/serv3.jpg" alt="Finance Management" class="MM__img">
                </div>
            </div>
        </a>
        <div class="loader-container" style="display: none;">
            <div class="spinner-loader"></div>
        </div>
</div>

<!-- Keeping the service overview section -->
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

<style>


.MM__cards_container {
    display: flex;
    flex-wrap: wrap;
    padding: 20px;
    gap: 20px;
    justify-content: center;
}

.MM__cards_container a .MM__text h2 {
    text-decoration: none;
}

.MM__cards {
    width: 230px;
    height: 270px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background-color: var(--white-color);
    border-radius: 10px;
    box-shadow: var(--box-shadow-form);
    font-family: "Outfit", sans-serif;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.MM__img {
    width: 200px;
    height: 200px;
    margin-bottom: 10px;
}

.MM__text {
    text-align: center;
    font-size: 1.2em;
    /* color: var(--dark-grey-color); */
    color: var(--primary-color) !important;
    font-weight: normal;
    text-decoration: none;
}

.MM__cards_container a {
    text-decoration: none;
    /* Removes underline */
    color: inherit;
    /* Inherits color from parent container */
}

.MM__cards_container a:hover {
    text-decoration: none;
    /* Ensures no underline on hover */
}

.MM__cards:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    /* Stronger shadow on hover */
}


.fixed_width_for_label {
    width: 100px !important;
}

.small_margin {
    margin: 5px !important;
}

/* .MM__cards_container {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr; 
    gap: 15px; 
    padding: 30px;
    max-width: 1200px;
    margin: 0 auto 40px;
}

.MM__cards {
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    height: 250px;
    position: relative;
    background-size: cover;
    background-position: center;
    display: block;
    width: 80%; 
}
 Lighter overlay gradient
.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.15) 60%, rgba(0,0,0,0.1) 100%);
    z-index: 1;
} */

/* .MM__text {
    color: #FFD600; 
    position: relative;
    z-index: 2;
    padding: 25px;
    height: 100%;
    width: 70%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.MM__text h2 {
    color: #FFD600;
    font-size: 26px; 
    margin-bottom: 15px;
    text-shadow: 1px 2px 3px rgba(0,0,0,0.5);
    font-weight: 600;
}

.MM__text p {
    color: rgba(255,255,255,0.95);
    font-size: 16px; /* Larger text */
    /* margin-bottom: 0;
    line-height: 1.5;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
    max-width: 90%;
} */

.task-count {
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: #ff5722;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 3;
}

/* .management-icon, .MM__img {
    display: none;
} */


.service-overview {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    margin: 0 auto 30px;
    overflow: hidden;
    max-width: 1200px;
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
    color: var(--primary-color) !important;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    color: #666;
}

@media (max-width: 768px) {
    .MM__cards_container {
        grid-template-columns: 1fr;
        padding: 20px;
    }
    
    .overview-stats {
        flex-direction: column;
    }
    
    .stat-item {
        margin-bottom: 15px;
    }
} 

/* Loader styles */
/* .loader-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.spinner-loader {
    width: 50px;
    height: 50px;
    border: 5px solid rgba(0, 0, 0, 0.1);
    border-top-color: #4e6ef7;
    border-radius: 50%;
    animation: spin 1s linear infinite;
} */

/* @keyframes spin {
    to {
        transform: rotate(360deg);
    }
} */
</style>

<script>
    //loader effect
    function displayLoader() {
        document.querySelector('.loader-container').style.display = '';
    }
    
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', displayLoader);
    });

    document.querySelectorAll('a').forEach(link => {
        if (!link.getAttribute('href').startsWith('#')) {
            link.addEventListener('click', displayLoader);
        }
    });
</script>

<?php require_once 'agentFooter.view.php'; ?>