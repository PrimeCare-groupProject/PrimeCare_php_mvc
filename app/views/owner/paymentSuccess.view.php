<?php require_once 'ownerHeader.view.php'; ?>

<div class="payment-success-container">
    <div class="payment-success-card">
        <div class="success-icon">
            <i class="fa-solid fa-check-circle"></i>
        </div>
        
        <h2>Payment Successful</h2>
        <p>Your payment of <strong>Rs. <?= number_format($amount, 2) ?></strong> has been processed successfully.</p>
        
        <div class="payment-details">
            <div class="detail-row">
                <span class="label">Invoice Number:</span>
                <span class="value"><?= $order_id ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Service ID:</span>
                <span class="value"><?= $service->service_id ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Service Type:</span>
                <span class="value"><?= $service->service_type ?? 'Regular Service' ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Date:</span>
                <span class="value"><?= date('Y-m-d', strtotime($payment_date)) ?></span>
            </div>
        </div>
        
        <div class="action-buttons">
            <button onclick="printInvoice()" class="download-invoice">
                <i class="fa-solid fa-print"></i> Print Invoice
            </button>
            <a href="<?= ROOT ?>/dashboard/maintenance" class="back-button">
                Return to Maintenance
            </a>
        </div>
    </div>
</div>

<!-- Hidden invoice that will be shown for printing -->
<div id="invoice-printable" style="display:none;">
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invoice #<?= $order_id ?></title>
        <style>
            * {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                background-color: white;
                padding: 20px;
            }
            
            .GR__report-container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
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
                .payment-success-container, 
                .action-buttons, 
                .back-btn {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
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
                        <span><?= $order_id ?></span>
                    </div>
                    <div class="GR__meta-field">
                        <label>Payment Date</label>
                        <span><?= date('Y-m-d', strtotime($payment_date)) ?></span>
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
                        <span><?= $provider_name ?></span>
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
                            <td>Rs. <?= number_format($amount, 2) ?></td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="invoice-total">
                    <h3>Total Amount:</h3>
                    <strong>Rs. <?= number_format($amount, 2) ?></strong>
                </div>
            </div>
            
            <div class="invoice-footer">
                <p>Thank you for your business! This is a computer-generated invoice and does not require a signature.</p>
                <p>For any questions, please contact customer service at 011-1234567 or primeCare@gmail.com</p>
            </div>
        </div>
    </body>
    </html>
</div>

<style>
.payment-success-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 200px);
    padding: 20px;
}

.payment-success-card {
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 40px;
    width: 100%;
    max-width: 600px;
    text-align: center;
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.success-icon {
    font-size: 80px;
    color: #4CAF50;
    margin-bottom: 20px;
    animation: scaleIn 0.5s ease-out 0.2s both;
}

@keyframes scaleIn {
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}

.payment-success-card h2 {
    color: #333;
    margin-bottom: 15px;
    font-size: 28px;
}

.payment-success-card p {
    color: #666;
    font-size: 18px;
    margin-bottom: 30px;
}

.payment-details {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.detail-row:last-child {
    border-bottom: none;
}

.label {
    font-weight: bold;
    color: #555;
}

.value {
    color: #333;
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.download-invoice, .back-button {
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    cursor: pointer;
}

.download-invoice {
    background-color: #4e6ef7;
    color: white;
}

.download-invoice:hover {
    background-color: #3a5ae8;
    transform: translateY(-2px);
}

.back-button {
    background-color: #f0f0f0;
    color: #333;
}

.back-button:hover {
    background-color: #e0e0e0;
}

/* Confetti animation */
.confetti {
    position: fixed;
    width: 10px;
    height: 10px;
    background-color: #f2d74e;
    opacity: 0;
    animation: confetti 5s ease-in-out infinite;
    z-index: 999;
}

@keyframes confetti {
    0% {
        opacity: 1;
        transform: translateY(0) rotateZ(0);
    }
    100% {
        opacity: 0;
        transform: translateY(100vh) rotateZ(720deg);
    }
}
</style>

<script>
// Create confetti effect
document.addEventListener('DOMContentLoaded', function() {
    createConfetti();
});

function createConfetti() {
    const colors = ['#f2d74e', '#95c3de', '#ff9a9a', '#a5de95', '#a09cde'];
    const confettiCount = 100;
    
    for (let i = 0; i < confettiCount; i++) {
        const confetti = document.createElement('div');
        confetti.classList.add('confetti');
        
        // Random position, color, and delay
        const left = Math.random() * 100;
        const backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        const delay = Math.random() * 5;
        const duration = 3 + Math.random() * 5;
        
        confetti.style.left = left + 'vw';
        confetti.style.backgroundColor = backgroundColor;
        confetti.style.animationDelay = delay + 's';
        confetti.style.animationDuration = duration + 's';
        
        document.body.appendChild(confetti);
        
        // Remove confetti after animation
        setTimeout(() => {
            confetti.remove();
        }, (delay + duration) * 1000);
    }
}

// Function to print the invoice
function printInvoice() {
    // Show the hidden invoice
    const invoiceElement = document.getElementById('invoice-printable');
    const originalDisplay = invoiceElement.style.display;
    
    // Open print dialog for just the invoice
    const printContent = invoiceElement.innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = printContent;
    window.print();
    
    // Restore original content
    document.body.innerHTML = originalContent;
    
    // Re-attach event listeners after DOM change
    document.querySelector('.download-invoice').addEventListener('click', printInvoice);
    
    // Run confetti again
    createConfetti();
}
</script>

<?php require_once 'ownerFooter.view.php'; ?>