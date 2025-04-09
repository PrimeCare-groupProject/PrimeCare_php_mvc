<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Make Payment</h2>
    <div class="flex-bar">
        <a href="<?= ROOT ?>dashboard/maintenance" style="display: inline-flex; align-items: center; color: #6c757d; text-decoration: none; font-weight: 500;" onmouseover="this.style.color='#343a40';" onmouseout="this.style.color='#6c757d';">
            <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back to Maintenance
        </a>
    </div>
</div>

<div class="container" style="max-width: 800px; margin: 20px auto;">
    <?php if (!empty($errors)): ?>
        <div style="padding: 12px 20px; color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; border-radius: 4px; margin-bottom: 15px;">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($status)): ?>
        <div style="padding: 12px 20px; color: #155724; background-color: #d4edda; border-color: #c3e6cb; border-radius: 4px; margin-bottom: 15px;">
            <?= $status ?>
        </div>
    <?php endif; ?>

    <div style="background-color: #f8f9fa; border-radius: 10px; padding: 20px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <h3>Service Details</h3>
        <div style="margin-top: 15px;">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Service ID:</strong> <?= $serviceLog->service_id ?? 'N/A' ?></p>
                    <p><strong>Service Type:</strong> <?= $serviceLog->service_type ?? 'Unknown Service' ?></p>
                    <p><strong>Property:</strong> <?= $serviceLog->property_name ?? 'Unknown Property' ?></p>
                    <p><strong>Date:</strong> <?= isset($serviceLog->date) ? date('Y/m/d', strtotime($serviceLog->date)) : 'N/A' ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Cost Per Hour:</strong> <?= number_format($serviceLog->cost_per_hour ?? 0, 2) ?> LKR</p>
                    <p><strong>Total Hours:</strong> <?= $serviceLog->total_hours ?? 0 ?></p>
                    <p><strong>Total Amount:</strong> <span style="font-size: 18px; font-weight: 700; color: #2c7be5;"><?= number_format(($serviceLog->cost_per_hour ?? 0) * ($serviceLog->total_hours ?? 0), 2) ?> LKR</span></p>
                </div>
            </div>
        </div>
    </div>

    <div style="background-color: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <h3>Payment Method</h3>
        <form action="<?= ROOT ?>dashboard/processPayment" method="post">
            <input type="hidden" name="service_id" value="<?= $serviceLog->service_id ?? '' ?>">
            <input type="hidden" name="amount" value="<?= ($serviceLog->cost_per_hour ?? 0) * ($serviceLog->total_hours ?? 0) ?>">
            
            <div style="display: flex; justify-content: space-between; margin: 20px 0;">
                <div style="flex: 1; margin: 0 10px; text-align: center; padding: 15px; border: 1px solid #e9ecef; border-radius: 5px; cursor: pointer; transition: all 0.2s;" onclick="document.getElementById('credit-card').checked = true; togglePaymentDetails();" onmouseover="this.style.borderColor='#2c7be5';" onmouseout="this.style.borderColor='#e9ecef';">
                    <input type="radio" id="credit-card" name="payment_method" value="credit-card" checked style="display: none;">
                    <label for="credit-card" style="cursor: pointer; display: block; margin: 0;">
                        <i class="fas fa-credit-card" style="display: block; font-size: 24px; margin-bottom: 10px; color: #6c757d;"></i> Credit Card
                    </label>
                </div>
                
                <div style="flex: 1; margin: 0 10px; text-align: center; padding: 15px; border: 1px solid #e9ecef; border-radius: 5px; cursor: pointer; transition: all 0.2s;" onclick="document.getElementById('paypal').checked = true; togglePaymentDetails();" onmouseover="this.style.borderColor='#2c7be5';" onmouseout="this.style.borderColor='#e9ecef';">
                    <input type="radio" id="paypal" name="payment_method" value="paypal" style="display: none;">
                    <label for="paypal" style="cursor: pointer; display: block; margin: 0;">
                        <i class="fab fa-paypal" style="display: block; font-size: 24px; margin-bottom: 10px; color: #6c757d;"></i> PayPal
                    </label>
                </div>
                
                <div style="flex: 1; margin: 0 10px; text-align: center; padding: 15px; border: 1px solid #e9ecef; border-radius: 5px; cursor: pointer; transition: all 0.2s;" onclick="document.getElementById('bank-transfer').checked = true; togglePaymentDetails();" onmouseover="this.style.borderColor='#2c7be5';" onmouseout="this.style.borderColor='#e9ecef';">
                    <input type="radio" id="bank-transfer" name="payment_method" value="bank-transfer" style="display: none;">
                    <label for="bank-transfer" style="cursor: pointer; display: block; margin: 0;">
                        <i class="fas fa-university" style="display: block; font-size: 24px; margin-bottom: 10px; color: #6c757d;"></i> Bank Transfer
                    </label>
                </div>
            </div>
            
            <div id="credit-card-details" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                <div class="form-group">
                    <label for="card-number">Card Number</label>
                    <input type="text" id="card-number" name="card_number" class="form-control" placeholder="1234 5678 9012 3456" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expiry-date">Expiry Date</label>
                            <input type="text" id="expiry-date" name="expiry_date" class="form-control" placeholder="MM/YY" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" class="form-control" placeholder="123" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="card-holder">Card Holder Name</label>
                    <input type="text" id="card-holder" name="card_holder" class="form-control" placeholder="John Doe" required>
                </div>
            </div>
            
            <div style="margin-top: 30px; text-align: right;">
                <button type="submit" style="background-color: #28a745; border: none; padding: 12px 24px; font-size: 16px; font-weight: 500; border-radius: 5px; color: white; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#218838';" onmouseout="this.style.backgroundColor='#28a745';">
                    Pay Now <span style="background-color: rgba(255,255,255,0.2); padding: 4px 8px; border-radius: 4px; margin-left: 8px;"><?= number_format(($serviceLog->cost_per_hour ?? 0) * ($serviceLog->total_hours ?? 0), 2) ?> LKR</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePaymentDetails() {
    // Toggle payment method details
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const creditCardDetails = document.getElementById('credit-card-details');
    
    for (let method of paymentMethods) {
        if (method.checked && method.value === 'credit-card') {
            creditCardDetails.style.display = 'block';
            return;
        }
    }
    creditCardDetails.style.display = 'none';
}

// Set initial state
document.addEventListener('DOMContentLoaded', function() {
    togglePaymentDetails();
    
    // Add click event to payment method labels
    const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
    paymentOptions.forEach(option => {
        option.addEventListener('change', togglePaymentDetails);
    });
});
</script>

<?php require_once 'ownerFooter.view.php'; ?>