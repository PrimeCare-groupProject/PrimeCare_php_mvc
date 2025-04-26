<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Service Management</h2>
</div>

<style>
    /* Larger card styles */
    .MM__cards_container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 40px;
        margin-top: 40px;
    }
    
    .MM__cards {
        width: 380px;
        height: 340px;
        background-color: white;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    
    .MM__cards:hover {
        transform: translateY(-15px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    .MM__text {
        height: 35%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 25px;
    }
    
    .MM__text h2 {
        font-size: 34px;
        text-align: center;
        margin: 0;
    }
    
    .management-icon {
        height: 65%;
        overflow: hidden;
    }
    
    .MM__img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .MM__cards:hover .MM__img {
        transform: scale(1.05);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .MM__cards {
            width: 330px;
            height: 300px;
        }
        
        .MM__text h2 {
            font-size: 24px;
        }
    }
    
    @media (max-width: 480px) {
        .MM__cards {
            width: 90%;
            height: 280px;
        }
    }
</style>

<div class="MM__cards_container">
        <a href="<?= ROOT ?>/dashboard/services">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Service Listing</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/serv2.jpg" alt="Service Listing" class="MM__img">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/agent/serviceApplications">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Service Applications</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/serv4.jpg" alt="Service Applications" class="MM__img">
                </div>
            </div>
        </a>
        
        <div class="loader-container" style="display: none;">
            <div class="spinner-loader"></div>
        </div>
</div>

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