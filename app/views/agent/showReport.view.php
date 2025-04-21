<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/preInspection'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Pre Inspection Report : <span style="color: var(--green-color)"><?= $property->name ?></span></h2>
    <div class="flex-bar">
        <button onclick="generatePDF('PreInspection' , <?= $property->property_id ?> )" class="small-btn orange">Download <i class="fa fa-download" style="margin-left: 5px;"></i></button>
    </div>
</div>

<!-- Copy this part and above button for every report -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

<div id="loading-spinner" style="display: none; height: 50px; width: 50px; margin: auto; text-align: center;">
    <img src="<?= ROOT ?>/assets/images/loading-spinner.gif" alt="Loading...">
    <p>Generating PDF...</p>
</div>
<div id="report-content" style="margin-bottom: 20px;">
<!-- include the report -->
    <?php require_once APPROOT . '/reports/preInsp.report.php'; ?>
</div>

<!-- Add this js file for every report calling -->
<script src="<?= ROOT ?>/assets/js/reports/reportSpecification.js"></script>

<?php require_once 'agentFooter.view.php'; ?>