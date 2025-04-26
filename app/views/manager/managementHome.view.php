<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>managements</h2>
</div>

<div class="MM__cards_container">
        <a href="<?= ROOT ?>/dashboard/managementhome/employeemanagement">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Profile</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/employeeManagement.jpg" alt="Employee Management" class="MM__img">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/managementhome/propertymanagement">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Property</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/addOwner.jpg" alt="Property Management" class="MM__img">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/managementhome/employeeListing">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Finance</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/financialManagement.jpg" alt="Finance Management" class="MM__img">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/managementhome/agentmanagement">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Agent</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/agentManagement.jpg" alt="Agent Management" class="MM__img">
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
        //onclick="displayLoader()"
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

<?php require_once 'managerFooter.view.php'; ?>