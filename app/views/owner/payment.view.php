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
        <?php
        // Calculate all necessary costs
        $usual_cost = $serviceLog->usual_cost ?? (($serviceLog->cost_per_hour ?? 0) * ($serviceLog->total_hours ?? 0));
        $additional_charges = $serviceLog->additional_charges ?? 0;
        $service_cost = $usual_cost + $additional_charges; // Total service cost before service charge
        $service_charge = $service_cost * 0.10; // 10% service charge
        $total_amount = $service_cost + $service_charge; // Final total amount
        ?>
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 30px; background: linear-gradient(135deg, #4a6bff 0%, #2541b8 100%); color: white;">
            <div>
                <h2 style="margin: 0; font-size: 24px; font-weight: 700;">Payment Details</h2>
                <p style="margin: 5px 0 0; opacity: 0.9; font-size: 15px;">Invoice #: <?= 'INV-' . date('Ymd') . '-' . rand(1000, 9999) ?></p>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 700; font-size: 28px;">
                    LKR <?= number_format($total_amount, 2) ?>
                </div>
                <div style="font-size: 14px; opacity: 0.9;">Total Amount Due</div>
            </div>
        </div>

        <!-- Service Images Section (if available) -->
        <?php
        $service_images = !empty($serviceLog->service_images) ? json_decode($serviceLog->service_images) : [];
        if (!empty($service_images)):
        ?>
            <div style="padding: 25px 30px; border-bottom: 1px solid #eee; background-color: #f8fbff;">
                <h3 style="margin: 0 0 20px; font-size: 18px; color: #444; display: flex; align-items: center;">
                    <i class="fas fa-images" style="margin-right: 10px; color: #4a6bff;"></i> Service Documentation
                </h3>

                <div style="position: relative;">
                    <div class="service-image-slider" style="display: flex; overflow-x: auto; gap: 15px; padding: 5px 0; scroll-behavior: smooth;">
                        <?php foreach ($service_images as $image): ?>
                            <div style="flex: 0 0 auto; width: 160px; height: 120px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); cursor: pointer;" onclick="openImageModal('<?= ROOT ?>/assets/images/uploads/service_logs/<?= htmlspecialchars($image) ?>')">
                                <img src="<?= ROOT ?>/assets/images/uploads/service_logs/<?= htmlspecialchars($image) ?>" alt="Service Image" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($service_images) > 4): ?>
                        <div style="position: absolute; top: 50%; transform: translateY(-50%); left: -15px; right: -15px; display: flex; justify-content: space-between; pointer-events: none;">
                            <button onclick="scrollImages('left')" style="width: 30px; height: 30px; border-radius: 50%; background-color: white; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2); cursor: pointer; display: flex; align-items: center; justify-content: center; pointer-events: auto;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button onclick="scrollImages('right')" style="width: 30px; height: 30px; border-radius: 50%; background-color: white; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2); cursor: pointer; display: flex; align-items: center; justify-content: center; pointer-events: auto;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Service Details Section -->
        <div style="padding: 25px 30px; border-bottom: 1px solid #eee;">
            <h3 style="margin: 0 0 20px; font-size: 18px; color: #444; display: flex; align-items: center;">
                <i class="fas fa-info-circle" style="margin-right: 10px; color: #4a6bff;"></i> Service Information
            </h3>

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

            <!-- Service Description Section -->
            <?php if (!empty($serviceLog->service_description) || !empty($serviceLog->service_provider_description)): ?>
                <div style="margin-top: 20px; background-color: #f9f9f9; padding: 15px; border-radius: 10px; border-left: 4px solid #4a6bff;">
                    <?php if (!empty($serviceLog->service_description)): ?>
                        <div style="margin-bottom: 15px;">
                            <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Request Description</div>
                            <div style="font-size: 15px; color: #444; line-height: 1.5;"><?= nl2br(htmlspecialchars($serviceLog->service_description)) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($serviceLog->service_provider_description)): ?>
                        <div>
                            <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Provider Notes</div>
                            <div style="font-size: 15px; color: #444; line-height: 1.5;"><?= nl2br(htmlspecialchars($serviceLog->service_provider_description)) ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Cost Breakdown Section -->
        <div style="padding: 25px 30px; border-bottom: 1px solid #eee; background-color: #f9f9f9;">
            <h3 style="margin: 0 0 20px; font-size: 18px; color: #444; display: flex; align-items: center;">
                <i class="fas fa-file-invoice-dollar" style="margin-right: 10px; color: #4a6bff;"></i> Cost Breakdown
            </h3>

            <div style="display: flex; flex-direction: column; gap: 15px;">
                <!-- Basic Service Cost -->
                <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px dashed #ddd;">
                    <div>
                        <div style="font-size: 15px; font-weight: 500; color: #555;">Hourly Rate</div>
                        <div style="font-size: 13px; color: #888;"><?= $serviceLog->total_hours ?? 0 ?> hours × <?= number_format($serviceLog->cost_per_hour ?? 0, 2) ?> LKR</div>
                    </div>
                    <div style="font-size: 15px; font-weight: 600; color: #333;">LKR <?= number_format($usual_cost, 2) ?></div>
                </div>

                <!-- Additional Charges (if any) -->
                <?php if ($additional_charges > 0): ?>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px dashed #ddd; background-color: #fef9e7; padding: 10px; border-radius: 5px;">
                        <div>
                            <div style="font-size: 15px; font-weight: 500; color: #555;">Additional Charges</div>
                            <?php if (!empty($serviceLog->additional_charges_reason)): ?>
                                <div style="font-size: 13px; color: #888;"><?= htmlspecialchars($serviceLog->additional_charges_reason) ?></div>
                            <?php endif; ?>
                        </div>
                        <div style="font-size: 15px; font-weight: 600; color: #333;">LKR <?= number_format($additional_charges, 2) ?></div>
                    </div>
                <?php endif; ?>

                <!-- Service Charge -->
                <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px dashed #ddd;">
                    <div>
                        <div style="font-size: 15px; font-weight: 500; color: #555;">Service Charge (10%)</div>
                    </div>
                    <div style="font-size: 15px; font-weight: 600; color: #f39c12;">LKR <?= number_format($service_charge, 2) ?></div>
                </div>

                <!-- Total Amount -->
                <div style="display: flex; justify-content: space-between; padding-top: 5px; margin-top: 5px; border-top: 2px solid #ddd;">
                    <div>
                        <div style="font-size: 16px; font-weight: 700; color: #333;">Total Amount</div>
                    </div>
                    <div style="font-size: 18px; font-weight: 800; color: #2c7be5;">LKR <?= number_format($total_amount, 2) ?></div>
                </div>
            </div>
        </div>

        <!-- Payment Method Section -->
        <!-- <div style="padding: 25px 30px;">
            <h3 style="margin: 0 0 20px; font-size: 18px; color: #444; display: flex; align-items: center;">
                <i class="fas fa-credit-card" style="margin-right: 10px; color: #4a6bff;"></i> Payment Method
            </h3>
            
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
                    Pay LKR <?= number_format($total_amount, 2) ?>
                </button>
            </div>
        </div> -->


        <?php
        $merchant_id = MERCHANT_ID;
        $merchant_secret = MERCHANT_SECRET;
        $order_id = uniqid("SERVICE_");
        $_SESSION['order_id'] = $order_id; // Store order ID in session for later use
        //$amount = number_format($total_amount, 2);
        //$_SESSION['amount'] = $amount; // Store amount in session for later use 
        $currency = "LKR";

        $amount_formatted = number_format($total_amount, 2, '.', '');

        $hash = strtoupper(
            md5(
                $merchant_id .
                    $order_id .
                    $amount_formatted .
                    $currency .
                    strtoupper(md5($merchant_secret))
            )
        );
        ?>
        <form method="post" action="https://sandbox.payhere.lk/pay/checkout" style="padding: 25px 30px;">
            <input type="hidden" name="merchant_id" value="<?= $merchant_id ?>">
            <!-- <input type="hidden" name="return_url" value="http://localhost/php_mvc_backend/public/Payment/success"> -->
            <input type="hidden" name="return_url" value="http://localhost/php_mvc_backend/public/dashboard/servicePayment/success?order_id=<?= $order_id ?>&amount=<?= $amount_formatted ?>&service_id=<?= $serviceLog->service_id ?>">
            <input type="hidden" name="cancel_url" value="http://localhost/php_mvc_backend/public/dashboard/servicePayment/cancel?order_id=<?= $order_id ?>&amount=<?= $amount_formatted ?>&service_id=<?= $serviceLog->service_id ?>">
            <input type="hidden" name="notify_url" value="http://localhost/php_mvc_backend/public/dashboard/servicePayment/notify">
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <input type="hidden" name="items" value="Test Item">
            <input type="hidden" name="currency" value="<?= $currency ?>">
            <input type="hidden" name="amount" value="<?= $amount_formatted ?>">
            <input type="hidden" name="hash" value="<?= $hash ?>">
            <input type="hidden" name="first_name" value="<?= $user->fname ?>">
            <input type="hidden" name="last_name" value="<?= $user->lname ?>">
            <input type="hidden" name="email" value="<?= $user->email ?>">
            <input type="hidden" name="phone" value="<?= $user->contact ?>">
            <input type="hidden" name="address" value="<?= $property->address ?>">
            <input type="hidden" name="city" value="<?= $property->city ?>">
            <input type="hidden" name="country" value="Sri Lanka">
            <div style="margin-top: 30px; text-align: center;">
                <button type="submit" style="padding: 15px 40px; background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%); color: white; border: none; border-radius: 30px; font-size: 16px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 12px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 6px rgba(40, 167, 69, 0.2)';">
                    Pay LKR <?= $amount_formatted ?>
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); overflow: auto; align-items: center; justify-content: center;">
    <span onclick="closeImageModal()" style="position: absolute; top: 20px; right: 30px; color: #f1f1f1; font-size: 35px; font-weight: bold; cursor: pointer;">&times;</span>
    <img id="modalImage" style="margin: auto; display: block; max-width: 90%; max-height: 90%; animation: zoomIn 0.3s;">
</div>

<!-- Payment Success Modal -->
<div id="paymentSuccessModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); overflow: auto; align-items: center; justify-content: center;">
    <div style="background-color: #fff; margin: auto; padding: 30px; border-radius: 15px; max-width: 500px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">
        <div style="width: 80px; height: 80px; margin: 0 auto 20px; background-color: #d4edda; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-check" style="font-size: 40px; color: #28a745;"></i>
        </div>
        <h2 style="color: #333; margin-bottom: 15px;">Payment Successful!</h2>
        <p style="color: #666; margin-bottom: 25px; font-size: 16px;">Your payment of LKR <?= number_format($total_amount, 2) ?> has been processed successfully.</p>
        <div style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; display: inline-block;">
            <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Transaction ID</div>
            <div style="font-size: 16px; font-weight: 600; color: #333;"><?= 'TXN-' . strtoupper(substr(md5(time()), 0, 10)) ?></div>
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
        document.getElementById('paymentSuccessModal').style.display = 'flex';

        // After 5 seconds, redirect to maintenance page
        setTimeout(function() {
            window.location.href = '<?= ROOT ?>/dashboard/maintenance';
        }, 5000);
    }

    function scrollImages(direction) {
        const container = document.querySelector('.service-image-slider');
        const scrollAmount = 180; // Width of one image + gap

        if (direction === 'left') {
            container.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        } else {
            container.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }
    }

    function openImageModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');

        modal.style.display = 'flex';
        modalImg.src = imageSrc;
    }

    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    // Add zoom-in animation
    document.head.insertAdjacentHTML('beforeend', `
        <style>
            @keyframes zoomIn {
                from {transform: scale(0.1); opacity: 0;}
                to {transform: scale(1); opacity: 1;}
            }
        </style>
    `);
</script>

<?php require_once 'ownerFooter.view.php'; ?>