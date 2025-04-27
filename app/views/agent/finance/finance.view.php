<?php require_once __DIR__ . '\..\agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Finance Overview</h2>
</div>


<div>
    <div class="OFR__selection_line" style="grid-template-columns: repeat(2, 1fr);">
        <div class="OFR__selection_line-item OFR__active" onclick="showTab(0)">
            <span>Overview</span>
        </div>
        <div class="OFR__selection_line-item" onclick="showTab(1)">
            <span>Salary Payments</span>
        </div>
        </div>
    </div>

    <div class="OFR__selection_line-content">
        <div class="OFR__selection_line-content-item OFR__active">
            <?php require 'overview.view.php'; ?>
        </div>
        <div class="OFR__selection_line-content-item">
            <?php require 'salary.view.php'; ?>
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


<?php require_once __DIR__ . '\..\agentFooter.view.php'; ?>