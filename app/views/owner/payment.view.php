<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar" style="margin-bottom: 20px;">
    <div class="gap"></div>
    <h2>Service Payment</h2>
    <div class="flex-bar">
        <a href="<?= ROOT ?>/dashboard/maintenance" style="display: inline-flex; align-items: center; text-decoration: none; color: #666; font-size: 14px;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back to Maintenance
        </a>
    </div>
</div>

<div style="max-width: 900px; margin: 0 auto; padding: 0 20px 40px;">
    <div style="background-color: #fff; border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); overflow: hidden; margin-bottom: 30px;">
        <!-- Payment Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 30px; background: linear-gradient(135deg, #4a6bff 0%, #2541b8 100%); color: white;">
            <div>
                <h2 style="margin: 0; font-size: 24px; font-weight: 700;">Payment Details</h2>
                <p style="margin: 5px 0 0; opacity: 0.9; font-size: 15px;">Invoice #: <?= 'INV-'.date('Ymd').'-'.rand(1000, 9999) ?></p>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 700; font-size: 28px;">
                    <?= number_format(($serviceLog->cost_per_hour ?? 0) * ($serviceLog->total_hours ?? 0), 2) ?> LKR
                </div>
                <div style="font-size: 14px; opacity: 0.9;">Total Amount</div>
            </div>
        </div>

        <!-- Service Details Section -->
        <div style="padding: 25px 30px; border-bottom: 1px solid #eee;">
            <h3 style="margin: 0 0 20px; font-size: 18px; color: #444;">Service Information</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Type</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $serviceLog->service_type ?? 'Unknown Service' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service ID</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $serviceLog->service_id ?? 'N/A' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Property</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $serviceLog->property_name ?? 'Unknown Property' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Date</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= isset($serviceLog->date) ? date('Y/m/d', strtotime($serviceLog->date)) : 'N/A' ?></div>
                </div>
            </div>
        </div>

        <!-- Cost Breakdown Section -->
        <div style="padding: 25px 30px; border-bottom: 1px solid #eee; background-color: #f9f9f9;">
            <h3 style="margin: 0 0 20px; font-size: 18px; color: #444;">Cost Breakdown</h3>
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px dashed #ddd;">
                    <div>
                        <div style="font-size: 15px; font-weight: 500; color: #555;">Hourly Rate</div>
                    </div>
                    <div style="font-size: 15px; font-weight: 600; color: #333;"><?= number_format($serviceLog->cost_per_hour ?? 0, 2) ?> LKR</div>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px dashed #ddd;">
                    <div>
                        <div style="font-size: 15px; font-weight: 500; color: #555;">Total Hours</div>
                    </div>
                    <div style="font-size: 15px; font-weight: 600; color: #333;"><?= $serviceLog->total_hours ?? 0 ?> hours</div>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding-top: 5px;">
                    <div>
                        <div style="font-size: 16px; font-weight: 700; color: #333;">Total Amount</div>
                    </div>
                    <div style="font-size: 18px; font-weight: 800; color: #2c7be5;"><?= number_format(($serviceLog->cost_per_hour ?? 0) * ($serviceLog->total_hours ?? 0), 2) ?> LKR</div>
                </div>
            </div>
        </div>
        
        <!-- Payment Method Section -->
        <div style="padding: 25px 30px;">
            <h3 style="margin: 0 0 20px; font-size: 18px; color: #444;">Payment Method</h3>
            
            <div style="display: flex; gap: 15px; margin-bottom: 25px;">
                <label style="flex: 1; padding: 15px; border: 1px solid #ddd; border-radius: 10px; cursor: pointer; transition: all 0.2s; background-color: #f8f9fa;" class="payment-option" onclick="selectPaymentMethod(this, 'credit_card')">
                    <input type="radio" name="payment_method" value="credit_card" style="margin-right: 10px;" checked>
                    <div style="display: inline-flex; align-items: center; gap: 10px;">
                        <i class="far fa-credit-card" style="font-size: 20px; color: #666;"></i>
                        <div style="font-weight: 600; color: #444;">Credit/Debit Card</div>
                    </div>
                </label>
                
                <label style="flex: 1; padding: 15px; border: 1px solid #ddd; border-radius: 10px; cursor: pointer; transition: all 0.2s;" class="payment-option" onclick="selectPaymentMethod(this, 'bank_transfer')">
                    <input type="radio" name="payment_method" value="bank_transfer" style="margin-right: 10px;">
                    <div style="display: inline-flex; align-items: center; gap: 10px;">
                        <i class="fas fa-university" style="font-size: 20px; color: #666;"></i>
                        <div style="font-weight: 600; color: #444;">Bank Transfer</div>
                    </div>
                </label>
            </div>
            
            <!-- Credit Card Form (visible by default) -->
            <div id="credit_card_form" class="payment_form">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">
                    <div style="grid-column: 1 / 3;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #555;">Card Number</label>
                        <input type="text" placeholder="1234 5678 9012 3456" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px;">
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #555;">Expiry Date</label>
                        <input type="text" placeholder="MM/YY" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px;">
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #555;">CVV</label>
                        <input type="text" placeholder="123" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px;">
                    </div>
                    
                    <div style="grid-column: 1 / 3;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #555;">Cardholder Name</label>
                        <input type="text" placeholder="John Smith" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px;">
                    </div>
                </div>
            </div>
            
            <!-- Bank Transfer Form (hidden by default) -->
            <div id="bank_transfer_form" class="payment_form" style="display: none;">
                <div style="background-color: #f0f4ff; border: 1px solid #d0d9f0; border-radius: 10px; padding: 15px; margin-bottom: 20px;">
                    <h4 style="margin-top: 0; margin-bottom: 15px; color: #2c7be5;">Bank Account Details</h4>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 10px; font-size: 15px;">
                        <div style="color: #555;">Bank Name:</div>
                        <div style="font-weight: 600; color: #333;">Bank of Ceylon</div>
                        
                        <div style="color: #555;">Account Name:</div>
                        <div style="font-weight: 600; color: #333;">HomeCare Services Ltd</div>
                        
                        <div style="color: #555;">Account Number:</div>
                        <div style="font-weight: 600; color: #333;">0123456789</div>
                        
                        <div style="color: #555;">Branch Code:</div>
                        <div style="font-weight: 600; color: #333;">001</div>
                        
                        <div style="color: #555;">Reference:</div>
                        <div style="font-weight: 600; color: #333;">SRV-<?= $serviceLog->service_id ?? '00000' ?></div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #555;">Upload Payment Proof (Optional)</label>
                    <input type="file" style="width: 100%; padding: 10px; border: 1px dashed #ddd; border-radius: 8px; background-color: #f9f9f9;">
                </div>
            </div>
            
            <div style="margin-top: 30px; text-align: center;">
                <button type="button" style="padding: 15px 40px; background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%); color: white; border: none; border-radius: 30px; font-size: 16px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 12px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 6px rgba(40, 167, 69, 0.2)';" onclick="showPaymentSuccess()">
                    Confirm Payment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Success Modal -->
<div id="paymentSuccessModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); overflow: auto; align-items: center; justify-content: center;">
    <div style="background-color: #fff; margin: auto; padding: 30px; border-radius: 15px; max-width: 500px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">
        <div style="width: 80px; height: 80px; margin: 0 auto 20px; background-color: #d4edda; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-check" style="font-size: 40px; color: #28a745;"></i>
        </div>
        <h2 style="color: #333; margin-bottom: 15px;">Payment Successful!</h2>
        <p style="color: #666; margin-bottom: 25px; font-size: 16px;">Your payment of <?= number_format(($serviceLog->cost_per_hour ?? 0) * ($serviceLog->total_hours ?? 0), 2) ?> LKR has been processed successfully.</p>
        <div style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; display: inline-block;">
            <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Transaction ID</div>
            <div style="font-size: 16px; font-weight: 600; color: #333;"><?= 'TXN-'.strtoupper(substr(md5(time()), 0, 10)) ?></div>
        </div>
        <div>
            <a href="<?= ROOT ?>/dashboard/maintenance" style="display: inline-block; padding: 12px 25px; background-color: #4a6bff; color: white; text-decoration: none; border-radius: 30px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#3551cc';" onmouseout="this.style.backgroundColor='#4a6bff';">
                Back to Maintenance
            </a>
        </div>
    </div>
</div>

<script>
    function selectPaymentMethod(element, method) {
        // Remove active class from all options
        document.querySelectorAll('.payment-option').forEach(option => {
            option.style.backgroundColor = '#f8f9fa';
            option.style.borderColor = '#ddd';
        });
        
        // Add active class to selected option
        element.style.backgroundColor = '#e8f0fe';
        element.style.borderColor = '#4a6bff';
        
        // Hide all payment forms
        document.querySelectorAll('.payment_form').forEach(form => {
            form.style.display = 'none';
        });
        
        // Show selected payment form
        document.getElementById(method + '_form').style.display = 'block';
    }
    
    function showPaymentSuccess() {
        // In a real app, this would submit the form data via AJAX
        // and show the success modal only after a successful response
        
        document.getElementById('paymentSuccessModal').style.display = 'flex';
        
        // After 5 seconds, redirect to maintenance page
        setTimeout(function() {
            window.location.href = '<?= ROOT ?>/dashboard/maintenance';
        }, 5000);
    }
</script>

<?php require_once 'ownerFooter.view.php'; ?>