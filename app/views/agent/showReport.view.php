<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/preInspection'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Pre Inspection Report : <span style="color: var(--green-color)"><?= $property->name ?></span></h2>
    <div class="flex-bar">
        <button onclick="generatePDF()" class="small-btn orange">Get PDF</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
<div id="loading-spinner" style="display: none; height: 50px; width: 50px; margin: auto; text-align: center;">
    <img src="<?= ROOT ?>/assets/images/loading-spinner.gif" alt="Loading...">
    <p>Generating PDF...</p>
</div>
<div id="report-content" style="margin-bottom: 20px;">
    <?php require_once APPROOT . '/reports/preInsp.report.php'; ?>
</div>

<script>
    function generatePDF() {
        var element = document.getElementById('report-content');
        
        // Show the loading spinner
        document.getElementById('loading-spinner').style.display = 'block';
        
        var options = {
            margin: 0, // Adjust margin size
            filename: 'pre_inspection_report.pdf', // Set PDF filename
            image: {
                type: 'jpeg',
                quality: 0.95
            }, // Optionally adjust image quality
            html2canvas: {
                scale: 4,
                logging: true,
                letterRendering: true
            }, // Set scale and logging for debugging
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait',
                compress: true
            } // A4 size in portrait
        };

        // Generate the PDF
        html2pdf(element, options).then(function () {
            // Hide the loading spinner when PDF generation is complete
            document.getElementById('loading-spinner').style.display = 'none';
        });
    }
</script>

<?php require_once 'agentFooter.view.php'; ?>