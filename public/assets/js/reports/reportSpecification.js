function generatePDF(report_name , reportID) {
    var element = document.getElementById('report-content');

    // Show the loading spinner
    document.getElementById('loading-spinner').style.display = 'block';

    var options = {
        margin: 0, // Adjust margin size
        filename: report_name + '_' + reportID, // Set PDF filename
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
