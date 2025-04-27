<?php require_once 'customerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Maintenance Services</h2>
</div>

<div class="MM__cards_container">
    <a href="<?= ROOT ?>/dashboard/serviceRequests">
        <div class="MM__cards">
            <div class="MM__text">
                <h2>Regular Maintenance</h2>
            </div>
            <div class="management-icon">
                <img src="<?= ROOT ?>/assets/images/serv1.jpg" alt="Regular Maintenance" class="MM__img">
            </div>
            <!-- Add count summary section -->
            <div class="service-summary" style="background-color: rgba(74, 107, 255, 0.1); padding: 8px 15px; border-radius: 8px; margin-top: 10px;">
                <div style="display: flex; justify-content: space-around; text-align: center; font-size: 12px;">
                    <div style="flex:1;">
                        <div style="font-weight: 600; color: #f39c12;"><?= $regularRequiresPayment ?></div>
                        <div style="color: #666;">Need Payment</div>
                    </div>
                    <div style="width:1px; background: linear-gradient(to bottom, #eee 10%, #bbb 50%, #eee 90%); margin: 0 12px;"></div>
                    <div style="flex:1;">
                        <div style="font-weight: 600; color: #3498db;"><?= $regularPendingCount ?></div>
                        <div style="color: #666;">Pending</div>
                    </div>
                    <div style="width:1px; background: linear-gradient(to bottom, #eee 10%, #bbb 50%, #eee 90%); margin: 0 12px;"></div>
                    <div style="flex:1;">
                        <div style="font-weight: 600; color: #27ae60;"><?= $regularCompletedCount ?></div>
                        <div style="color: #666;">Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <a href="<?= ROOT ?>/dashboard/maintenanceHub/externalMai/maintenanceHubntenance">
        <div class="MM__cards">
            <div class="MM__text">
                <h2>External Maintenance</h2>
            </div>
            <div class="management-icon">
                <img src="<?= ROOT ?>/assets/images/serv4.jpg" alt="External Maintenance" class="MM__img">
            </div>
            <!-- Add count summary section -->
            <div class="service-summary" style="background-color: rgba(39, 174, 96, 0.1); padding: 8px 15px; border-radius: 8px; margin-top: 10px;">
                <div style="display: flex; justify-content: space-around; text-align: center; font-size: 12px;">
                    <div style="flex:1;">
                        <div style="font-weight: 600; color: #f39c12;"><?= $externalRequiresPayment ?></div>
                        <div style="color: #666;">Need Payment</div>
                    </div>
                    <div style="width:1px; background: linear-gradient(to bottom, #eee 10%, #bbb 50%, #eee 90%); margin: 0 12px;"></div>
                    <div style="flex:1;">
                        <div style="font-weight: 600; color: #3498db;"><?= $externalPendingCount ?></div>
                        <div style="color: #666;">Pending</div>
                    </div>
                    <div style="width:1px; background: linear-gradient(to bottom, #eee 10%, #bbb 50%, #eee 90%); margin: 0 12px;"></div>
                    <div style="flex:1;">
                        <div style="font-weight: 600; color: #27ae60;"><?= $externalCompletedCount ?></div>
                        <div style="color: #666;">Completed</div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </a>
    
    <!-- Keep the loader container at the end -->
    <div class="loader-container" style="display: none;">
        <div class="spinner-loader"></div>
    </div>
</div>

<style>
    .MM__cards{
        width : 400px;
        height : 400px;
    }
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

<?php require_once 'customerFooter.view.php'; ?>