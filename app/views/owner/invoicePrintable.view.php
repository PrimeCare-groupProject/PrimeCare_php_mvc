<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $payment->invoice_number ?></title>
    <style>
        * {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .action-bar {
            padding: 15px;
            background-color: #4e6ef7;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .action-bar button {
            background-color: white;
            color: #4e6ef7;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .action-bar button:hover {
            background-color: #f0f0f0;
        }
        
        .back-btn {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .GR__report-container {
            padding: 30px;
        }
        
        .GR__report-header {
            display: flex;
            flex-direction: column;
            border-bottom: 2px solid #4e6ef7;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .GR__title-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .GR__logo img {
            height: 80px;
            width: auto;
        }
        
        .GR__company-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .GR__report-title h1 {
            color: #4e6ef7;
            margin: 0;
            font-size: 24px;
        }
        
        .GR__meta-info {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .GR__meta-field {
            margin-bottom: 10px;
            flex-basis: 30%;
        }
        
        .GR__meta-field label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
            color: #555;
        }
        
        .GR__meta-field span {
            display: block;
            font-size: 14px;
        }
        
        .invoice-content {
            margin-top: 30px;
        }
        
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .invoice-table th, .invoice-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        
        .invoice-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        
        .invoice-total {
            margin-top: 30px;
            text-align: right;
        }
        
        .invoice-total h3 {
            display: inline-block;
            margin-right: 20px;
        }
        
        .invoice-footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        
        @media print {
            .action-bar, .back-btn {
                display: none !important;
            }
            
            body {
                background-color: white;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="action-bar">
            <a href="<?= ROOT ?>/dashboard/maintenance" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <div>
                <button onclick="window.print()">
                    <i class="fa-solid fa-print"></i> Print Invoice
                </button>
                <button onclick="downloadAsPDF()">
                    <i class="fa-solid fa-download"></i> Save as PDF
                </button>
            </div>
        </div>
        
        <div class="GR__report-container">
            <div class="GR__report-header">
                <div class="GR__title-area">
                    <div class="GR__logo">
                        <img src="<?= ROOT ?>/assets/images/logo.png" alt="Company Logo">
                    </div>
                    <div class="GR__company-info">
                        <p>No 9, Marine drive,<br>Bambalapitiya</p>
                        <p>primeCare@gmail.com ✉</p>
                        <p>011-1234567 ☎</p>
                    </div>
                </div>
    
                <div class="GR__title-area">
                    <div class="GR__report-title">
                        <h1>Service Payment Invoice</h1>
                    </div>
                </div>
    
                <div class="GR__meta-info">
                    <div class="GR__meta-field">
                        <label>Invoice #</label>
                        <span><?= $payment->invoice_number ?></span>
                    </div>
                    <div class="GR__meta-field">
                        <label>Payment Date</label>
                        <span><?= date('Y-m-d', strtotime($payment->payment_date)) ?></span>
                    </div>
                    <div class="GR__meta-field">
                        <label>Service ID</label>
                        <span><?= $service->service_id ?></span>
                    </div>
                </div>
            </div>
            
            <div class="invoice-content">
                <h2>Invoice Details</h2>
                
                <div class="GR__meta-info">
                    <div class="GR__meta-field">
                        <label>Property</label>
                        <span><?= $property->name ?? 'N/A' ?></span>
                    </div>
                    <div class="GR__meta-field">
                        <label>Property Address</label>
                        <span><?= $property->address ?? 'N/A' ?></span>
                    </div>
                    <div class="GR__meta-field">
                        <label>Service Provider</label>
                        <span><?= $providerName ?></span>
                    </div>
                </div>
                
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Description</th>
                            <th>Service Date</th>
                            <th>Rate</th>
                            <th>Hours</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $service->service_type ?? 'Service' ?></td>
                            <td><?= $service->service_description ?? 'N/A' ?></td>
                            <td><?= date('Y-m-d', strtotime($service->date)) ?></td>
                            <td>Rs. <?= number_format($service->cost_per_hour, 2) ?></td>
                            <td><?= $service->total_hours ?></td>
                            <td>Rs. <?= number_format($payment->amount, 2) ?></td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="invoice-total">
                    <h3>Total Amount:</h3>
                    <strong>Rs. <?= number_format($payment->amount, 2) ?></strong>
                </div>
            </div>
            
            <div class="invoice-footer">
                <p>Thank you for your business! This is a computer-generated invoice and does not require a signature.</p>
                <p>For any questions, please contact customer service at 011-1234567 or primeCare@gmail.com</p>
            </div>
        </div>
    </div>

    <script>
    function downloadAsPDF() {
        // Simply trigger the print dialog - most browsers allow saving as PDF
        window.print();
        
        // Alternative instruction for users
        setTimeout(function() {
            alert("To save as PDF: In the print dialog, select 'Save as PDF' or 'Microsoft Print to PDF' as the printer");
        }, 500);
    }
    </script>
</body>
</html>