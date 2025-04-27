<?php require_once 'customerHeader.view.php'; ?>

<div class="user_view-menu-bar" style="margin-bottom: 20px;">
    <div class="gap"></div>
    <h2>Payment Successful</h2>
    <div class="flex-bar">
        <a href="<?= ROOT ?>/dashboard/externalMaintenance" style="display: inline-flex; align-items: center; text-decoration: none; color: #666; font-size: 14px;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back to External Services
        </a>
    </div>
</div>

<div style="max-width: 900px; margin: 0 auto; padding: 0 20px 40px;">
    <div style="background-color: #fff; border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); overflow: hidden; margin-bottom: 30px;">
        <!-- Success Banner -->
        <div style="background-color: #d4edda; padding: 30px; text-align: center;">
            <div style="width: 80px; height: 80px; margin: 0 auto 20px; background-color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-check" style="font-size: 40px; color: #28a745;"></i>
            </div>
            <h2 style="color: #155724; margin-bottom: 10px;">Payment Successful!</h2>
            <p style="color: #155724; margin-bottom: 5px; font-size: 16px;">Your payment of LKR <?= number_format($amount, 2) ?> has been processed successfully.</p>
            <p style="color: #155724; font-size: 14px;">Transaction ID: <?= $order_id ?></p>
        </div>

        <!-- Payment Details -->
        <div style="padding: 25px 30px; border-bottom: 1px solid #eee;">
            <h3 style="margin: 0 0 20px; font-size: 18px; color: #444; display: flex; align-items: center;">
                <i class="fas fa-info-circle" style="margin-right: 10px; color: #4a6bff;"></i> Payment Details
            </h3>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Type</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $service->service_type ?? 'External Service' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">External Service ID</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $service->id ?? 'N/A' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Property Address</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $property->address ?? $service->property_address ?? 'N/A' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Payment Date</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= date('Y-m-d H:i', strtotime($payment_date)) ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Amount Paid</div>
                    <div style="font-size: 16px; font-weight: 600; color: #28a745;">LKR <?= number_format($amount, 2) ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Provider</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $provider_name ?></div>
                </div>
            </div>
        </div>

        <!-- Invoice Download Button -->
        <div style="padding: 25px 30px; text-align: center;">
            <p style="margin-bottom: 20px; color: #666;">A copy of your receipt has been sent to your email.</p>
            <a href="#" onclick="printInvoice(); return false;" style="display: inline-flex; align-items: center; background-color: #4a6bff; color: white; text-decoration: none; padding: 12px 24px; border-radius: 30px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#3551cc';" onmouseout="this.style.backgroundColor='#4a6bff';">
                <i class="fas fa-file-invoice" style="margin-right: 8px;"></i> Download Invoice
            </a>
        </div>
    </div>

    <!-- Next Steps Card -->
    <div style="background-color: #fff; border-radius: 15px; padding: 25px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <h3 style="margin: 0 0 20px; font-size: 18px; color: #444;">What's Next?</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
            <a href="<?= ROOT ?>/dashboard/externalMaintenance" style="text-decoration: none; color: inherit;">
                <div style="background-color: #f8f9fa; border-radius: 10px; padding: 20px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#e9ecef';" onmouseout="this.style.backgroundColor='#f8f9fa';">
                    <div style="font-size: 20px; color: #4a6bff; margin-bottom: 10px;"><i class="fas fa-clipboard-list"></i></div>
                    <h4 style="margin: 0 0 10px; font-size: 16px;">View Your External Services</h4>
                    <p style="margin: 0; color: #666; font-size: 14px;">Check the status of your other external service requests</p>
                </div>
            </a>
            
            <a href="<?= ROOT ?>/dashboard" style="text-decoration: none; color: inherit;">
                <div style="background-color: #f8f9fa; border-radius: 10px; padding: 20px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#e9ecef';" onmouseout="this.style.backgroundColor='#f8f9fa';">
                    <div style="font-size: 20px; color: #4a6bff; margin-bottom: 10px;"><i class="fas fa-home"></i></div>
                    <h4 style="margin: 0 0 10px; font-size: 16px;">Return to Dashboard</h4>
                    <p style="margin: 0; color: #666; font-size: 14px;">Go back to your main customer dashboard</p>
                </div>
            </a>
            
            <a href="<?= ROOT ?>/dashboard/externalRepairListing" style="text-decoration: none; color: inherit;">
                <div style="background-color: #f8f9fa; border-radius: 10px; padding: 20px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#e9ecef';" onmouseout="this.style.backgroundColor='#f8f9fa';">
                    <div style="font-size: 20px; color: #4a6bff; margin-bottom: 10px;"><i class="fas fa-tools"></i></div>
                    <h4 style="margin: 0 0 10px; font-size: 16px;">Request New Service</h4>
                    <p style="margin: 0; color: #666; font-size: 14px;">Browse available external maintenance services</p>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
function printInvoice() {
    // Create a new window for the invoice
    var invoiceWindow = window.open('', '_blank');
    
    // Generate invoice HTML with proper variable handling for external services
    var invoiceHTML = `
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>External Service Payment Invoice</title>
        <style>
            * {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                padding: 20px;
            }
            .invoice-container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #eee;
            }
            .invoice-header {
                display: flex;
                justify-content: space-between;
                padding-bottom: 20px;
                border-bottom: 2px solid #4a6bff;
                margin-bottom: 20px;
            }
            .invoice-title {
                font-size: 24px;
                color: #4a6bff;
                margin-bottom: 5px;
            }
            .invoice-details {
                margin-bottom: 30px;
            }
            .invoice-details-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
            }
            .invoice-details-label {
                font-weight: bold;
                color: #555;
            }
            .invoice-service {
                margin-bottom: 30px;
            }
            .invoice-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            .invoice-table th, .invoice-table td {
                padding: 10px;
                border: 1px solid #ddd;
                text-align: left;
            }
            .invoice-table th {
                background-color: #f8f9fa;
            }
            .invoice-total {
                text-align: right;
                margin-top: 30px;
                font-size: 18px;
                font-weight: bold;
            }
            .invoice-footer {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
                font-size: 12px;
                color: #777;
                text-align: center;
            }
            @media print {
                body {
                    padding: 0;
                }
                .invoice-container {
                    border: none;
                }
                .no-print {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <div class="invoice-container">
            <div class="invoice-header">
                <div>
                    <h1 class="invoice-title">INVOICE</h1>
                    <p>PrimeCare External Services</p>
                </div>
                <div>
                    <img src="<?= ROOT ?>/assets/images/logo.png" alt="PrimeCare Logo" style="height: 60px;">
                </div>
            </div>
            
            <div class="invoice-details">
                <div class="invoice-details-row">
                    <div>
                        <div class="invoice-details-label">Invoice Number:</div>
                        <div>${'<?= $order_id ?>'}</div>
                    </div>
                    <div>
                        <div class="invoice-details-label">Date:</div>
                        <div>${new Date('<?= $payment_date ?>').toLocaleDateString()}</div>
                    </div>
                </div>
                
                <div class="invoice-details-row" style="margin-top: 20px;">
                    <div>
                        <div class="invoice-details-label">Billed To:</div>
                        <div>${'<?= $_SESSION['user']->fname . ' ' . $_SESSION['user']->lname ?>'}</div>
                        <div>${'<?= $_SESSION['user']->email ?>'}</div>
                    </div>
                    <div>
                        <div class="invoice-details-label">Service Provider:</div>
                        <div>${'<?= $provider_name ?>'}</div>
                    </div>
                </div>
            </div>
            
            <div class="invoice-service">
                <h3>Service Details</h3>
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>Service Description</th>
                            <th>Property</th>
                            <th>Service Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${'<?= $service->service_type ?>'}</td>
                            <td>${'<?= $service->property_address ?? "N/A" ?>'}</td>
                            <td>${new Date('<?= $service->date ?>').toLocaleDateString()}</td>
                            <td>LKR ${parseFloat('<?= $amount ?>').toFixed(2)}</td>
                        </tr>
                    </tbody>
                </table>
                
                <div style="margin-top: 20px; font-size: 14px;">
                    <p><strong>Service Description:</strong> ${'<?= htmlspecialchars($service->property_description ?? "") ?>'}</p>
                </div>
            </div>
            
            <div class="invoice-total">
                Total Amount: LKR ${parseFloat('<?= $amount ?>').toFixed(2)}
            </div>
            
            <div class="invoice-footer">
                <p>Thank you for your business! This is a computer-generated invoice and does not require a signature.</p>
                <p>For any questions about external services, please contact customer service at 011-1234567 or primeCare@gmail.com</p>
            </div>
            
            <div class="no-print" style="text-align: center; margin-top: 30px;">
                <button onclick="window.print()" style="padding: 10px 20px; background: #4a6bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Print Invoice</button>
            </div>
        </div>
    </body>
    </html>
    `;
    
    // Write the HTML to the new window
    invoiceWindow.document.write(invoiceHTML);
    
    // Necessary to properly load the document before printing
    invoiceWindow.document.close();
}
</script>

<?php require_once 'customerFooter.view.php'; ?>