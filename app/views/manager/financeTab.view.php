<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <h2>Financial Overview</h2>
</div>

<div>
    <div class="OFR__selection_line">
        <div class="OFR__selection_line-item OFR__active" onclick="showTab(0)">
            <span>Overview</span>
        </div>
        <div class="OFR__selection_line-item" onclick="showTab(1)">
            <span>Salary Payments</span>
        </div>
        <div class="OFR__selection_line-item" onclick="showTab(2)">
            <span>Ledger</span>
        </div>
    </div>

    <div class="OFR__selection_line-content">
        <div class="OFR__selection_line-content-item OFR__active">
            <?php require __DIR__ . '/finance/overview.view.php'; ?>
        </div>
        <div class="OFR__selection_line-content-item">
            <?php require __DIR__ . '/finance/salaryView.view.php'; ?>
        </div>
        <div class="OFR__selection_line-content-item">
            <?php require __DIR__ . '/finance/ledger.view.php'; ?>
        </div>
    </div>
</div>
<script>
    function showTab(tabIndex) {
        const tabButtons = document.querySelectorAll('.OFR__selection_line-item');
        const tabContents = document.querySelectorAll('.OFR__selection_line-content-item');

        tabButtons.forEach((button, index) => {
            button.classList.toggle('OFR__active', index === tabIndex);
        });

        tabContents.forEach((content, index) => {
            content.classList.toggle('OFR__active', index === tabIndex);
        });
    }
</script>

<?php require_once 'managerFooter.view.php'; ?>
