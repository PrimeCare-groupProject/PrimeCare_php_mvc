<?php require_once 'customerHeader.view.php'; ?>

<div class="user_view-menu-bar" style="margin-bottom: 20px;">
    <div class="gap"></div>
    <h2>External Service Payment</h2>
    <div class="flex-bar">
        <a href="<?= ROOT ?>/dashboard/externalMaintenance" style="display: inline-flex; align-items: center; text-decoration: none; color: #666; font-size: 14px;">
            <i class="fas fa-arrow-left" style="margin-right: 8px; color: #4a6bff;"></i> Back to Maintenance
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
                    LKR <?= number_format($total_amount, 2) ?>
                </div>
                <div style="font-size: 14px; opacity: 0.9;">Total Amount Due</div>
            </div>
        </div>

        <!-- Service Completion Images Section (if available) -->
        <?php 
        $completion_images = !empty($service->service_completion_images) ? json_decode($service->service_completion_images) : [];
        if (!empty($completion_images)): 
        ?>
        <div style="padding: 25px 30px; border-bottom: 1px solid #eee; background-color: #f8fbff;">
            <h3 style="margin: 0 0 20px; font-size: 18px; color: #444; display: flex; align-items: center;">
                <i class="fas fa-clipboard-check" style="margin-right: 10px; color: #4a6bff;"></i> Service Completion Documentation
            </h3>
            
            <div style="position: relative;">
                <div class="service-image-slider" style="display: flex; overflow-x: auto; gap: 15px; padding: 5px 0; scroll-behavior: smooth;">
                    <?php foreach ($completion_images as $image): ?>
                    <div style="flex: 0 0 auto; width: 160px; height: 120px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); cursor: pointer;" onclick="openImageModal('<?= ROOT ?>/assets/images/<?= htmlspecialchars($image) ?>')">
                        <img src="<?= ROOT ?>/assets/images/<?= htmlspecialchars($image) ?>" alt="Service Completion Image" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($completion_images) > 4): ?>
                <div style="position: absolute; top: 50%; transform: translateY(-50%); left: -15px; right: -15px; display: flex; justify-content: space-between; pointer-events: none;">
                    <button onclick="scrollImages('left')" style="width: 30px; height: 30px; border-radius: 50%; background-color: white; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2); cursor: pointer; display: flex; align-items: center; justify-content: center; pointer-events: auto;">
                        <i class="fas fa-chevron-left" style="color: #4a6bff;"></i>
                    </button>
                    <button onclick="scrollImages('right')" style="width: 30px; height: 30px; border-radius: 50%; background-color: white; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2); cursor: pointer; display: flex; align-items: center; justify-content: center; pointer-events: auto;">
                        <i class="fas fa-chevron-right" style="color: #4a6bff;"></i>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Original Service Request Images (if available) -->
        <?php 
        $service_images = !empty($service->service_images) ? json_decode($service->service_images) : [];
        if (!empty($service_images)): 
        ?>
        <div style="padding: 25px 30px; border-bottom: 1px solid #eee; background-color: #f9fafc;">
            <h3 style="margin: 0 0 20px; font-size: 18px; color: #444; display: flex; align-items: center;">
                <i class="fas fa-images" style="margin-right: 10px; color: #4a6bff;"></i> Property Images
            </h3>
            
            <div style="position: relative;">
                <div class="request-image-slider" style="display: flex; overflow-x: auto; gap: 15px; padding: 5px 0; scroll-behavior: smooth;">
                    <?php foreach ($service_images as $image): ?>
                    <div style="flex: 0 0 auto; width: 140px; height: 100px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); cursor: pointer; opacity: 0.8;" onclick="openImageModal('<?= ROOT ?>/assets/images/<?= htmlspecialchars($image) ?>')">
                        <img src="<?= ROOT ?>/assets/images/<?= htmlspecialchars($image) ?>" alt="Property Images" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'; this.parentNode.style.opacity='1';" onmouseout="this.style.transform='scale(1)'; this.parentNode.style.opacity='0.8';">
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($service_images) > 4): ?>
                <div style="position: absolute; top: 50%; transform: translateY(-50%); left: -15px; right: -15px; display: flex; justify-content: space-between; pointer-events: none;">
                    <button onclick="scrollRequestImages('left')" style="width: 30px; height: 30px; border-radius: 50%; background-color: white; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2); cursor: pointer; display: flex; align-items: center; justify-content: center; pointer-events: auto;">
                        <i class="fas fa-chevron-left" style="color: #4a6bff;"></i>
                    </button>
                    <button onclick="scrollRequestImages('right')" style="width: 30px; height: 30px; border-radius: 50%; background-color: white; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2); cursor: pointer; display: flex; align-items: center; justify-content: center; pointer-events: auto;">
                        <i class="fas fa-chevron-right" style="color: #4a6bff;"></i>
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
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $service->service_type ?? 'Unknown Service' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service ID</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;">#<?= $service->id ?? 'N/A' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Address</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $service->property_address ?? 'Unknown Location' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Date</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= isset($service->date) ? date('Y/m/d', strtotime($service->date)) : 'N/A' ?></div>
                </div>
            </div>
            
            <!-- Service Description Section -->
            <?php if (!empty($service->property_description)): ?>
            <div style="margin-top: 20px; background-color: #f9f9f9; padding: 15px; border-radius: 10px; border-left: 4px solid #4a6bff;">
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Property Description</div>
                    <div style="font-size: 15px; color: #444; line-height: 1.5;"><?= nl2br(htmlspecialchars($service->property_description)) ?></div>
                </div>
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
                        <div style="font-size: 13px; color: #888;"><?= $service->total_hours ?? 0 ?> hours × <?= number_format($service->cost_per_hour ?? 0, 2) ?> LKR</div>
                    </div>
                    <div style="font-size: 15px; font-weight: 600; color: #333;">LKR <?= number_format($usual_cost, 2) ?></div>
                </div>
                
                <!-- Additional Charges (if any) -->
                <?php if ($additional_charges > 0): ?>
                <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px dashed #ddd; background-color: #fef9e7; padding: 10px; border-radius: 5px;">
                    <div>
                        <div style="font-size: 15px; font-weight: 500; color: #555;">Additional Charges</div>
                        <?php if (!empty($service->additional_charges_reason)): ?>
                        <div style="font-size: 13px; color: #888;"><?= htmlspecialchars($service->additional_charges_reason) ?></div>
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
        <div style="padding: 25px 30px;">
            <h3 style="margin: 0 0 20px; font-size: 18px; color: #444; display: flex; align-items: center;">
                <i class="fas fa-credit-card" style="margin-right: 10px; color: #4a6bff;"></i> Payment Method
            </h3>
            
            <form id="payment-form" enctype="multipart/form-data" method="post" style="margin: 0;">
                <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
                    <label style="flex: 1; padding: 15px; border: 1px solid #ddd; border-radius: 10px; cursor: pointer; transition: all 0.2s; background-color: #e8f0fe; border-color: #4a6bff;" class="payment-option" onclick="selectPaymentMethod(this, 'payhere')">
                        <input type="radio" name="payment_method" value="payhere" style="margin-right: 10px;" checked>
                        <div style="display: inline-flex; align-items: center; gap: 10px;">
                            <i class="fas fa-globe" style="font-size: 20px; color: #4a6bff;"></i>
                            <div style="font-weight: 600; color: #444;">Online Payment (PayHere)</div>
                        </div>
                    </label>
                    
                    <label style="flex: 1; padding: 15px; border: 1px solid #ddd; border-radius: 10px; cursor: pointer; transition: all 0.2s; background-color: #f8f9fa;" class="payment-option" onclick="selectPaymentMethod(this, 'credit_card')">
                        <input type="radio" name="payment_method" value="credit_card" style="margin-right: 10px;">
                        <div style="display: inline-flex; align-items: center; gap: 10px;">
                            <i class="far fa-credit-card" style="font-size: 20px; color: #4a6bff;"></i>
                            <div style="font-weight: 600; color: #444;">Credit/Debit Card</div>
                        </div>
                    </label>
                </div>

                <!-- PayHere Form -->
                <div id="payhere_form" class="payment_form">
                    <div style="background-color: #f0f7ff; border-radius: 12px; padding: 20px; margin-bottom: 25px; border: 1px solid #cce1ff; box-shadow: 0 2px 10px rgba(74, 107, 255, 0.08);">
                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #e0edff 0%, #d0e3ff 100%); display: flex; align-items: center; justify-content: center; margin-right: 16px; box-shadow: 0 3px 6px rgba(74, 107, 255, 0.15);">
                                <i class="fas fa-shield-alt" style="color: #4a6bff; font-size: 22px;"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px; color: #333; font-weight: 600;">Secure Online Payment</h4>
                                <p style="margin: 5px 0 0; color: #666; font-size: 14px; line-height: 1.4;">Your transaction is secured with industry-standard encryption</p>
                            </div>
                        </div>
                        
                        <!-- PayHere Logo Section -->
                        <div style="text-align: center; margin: 10px 0 20px;">
                            <!-- Primary PayHere logo with fallback -->
                            <div style="display: flex; align-items: center; justify-content: center; gap: 6px; background: white; padding: 8px 15px; border-radius: 8px; box-shadow: 0 1px 5px rgba(0,0,0,0.1); display: inline-block;">
                                <!-- PayHere logo (text-based fallback) -->
                                <div style="font-size: 24px; font-weight: 700; color: #2874f0;">
                                    <span style="color: #2874f0;">Pay</span><span style="color: #20b038;">Here</span>
                                </div>
                            </div>
                            
                            <!-- Alternative image sources (attempting multiple sources) -->
                            <div style="margin-top: 10px;">
                                <img src="https://www.payhere.lk/images/logo-dark.png" 
                                    onerror="this.onerror=null; this.src='https://www.payhere.lk/downloads/images/payhere_logo_dark.png'; this.onerror=function(){this.style.display='none';}" 
                                    alt="PayHere" style="height: 30px; width: auto; filter: drop-shadow(0 1px 3px rgba(0,0,0,0.1));">
                            </div>
                        </div>
                                                
                        <!-- Payment Cards Section - Using Font Awesome Icons instead of images -->
                        <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center; justify-content: center; margin-top: 18px; padding-top: 15px; border-top: 1px dashed #cce1ff;">
                            <div style="background: linear-gradient(135deg, #fff 0%, #f6f6f6 100%); padding: 8px 12px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-cc-visa" style="font-size: 28px; color: #1A1F71;"></i>
                            </div>
                            <div style="background: linear-gradient(135deg, #fff 0%, #f6f6f6 100%); padding: 8px 12px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-cc-mastercard" style="font-size: 28px; color: #EB001B;"></i>
                            </div>
                            <div style="background: linear-gradient(135deg, #fff 0%, #f6f6f6 100%); padding: 8px 12px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-cc-amex" style="font-size: 28px; color: #006FCF;"></i>
                            </div>
                            <div style="background: linear-gradient(135deg, #fff 0%, #f6f6f6 100%); padding: 8px 12px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-cc-discover" style="font-size: 28px; color: #FF6000;"></i>
                            </div>
                            <span style="margin-left: 10px; font-size: 14px; color: #666;">+ more payment options</span>
                        </div>
                    </div>
                    
                    <div style="background-color: #fafbff; border-radius: 12px; padding: 20px; margin-bottom: 20px; border: 1px solid #eaeeff;">
                        <div style="margin-bottom: 15px; display: flex; align-items: flex-start;">
                            <div style="width: 70px; height: 70px; min-width: 70px; border-radius: 8px; background-color: #2874f0; color: white; display: flex; align-items: center; justify-content: center; margin-right: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <i class="fas fa-credit-card" style="font-size: 32px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 15px; font-weight: 500; color: #444; margin-bottom: 5px;">
                                    <i class="fas fa-info-circle" style="color: #4a6bff; margin-right: 8px;"></i> How PayHere Works
                                </div>
                                <p style="margin: 5px 0 0; color: #666; font-size: 14px; line-height: 1.5;">
                                    After clicking the payment button, you'll be securely redirected to PayHere to complete your payment. Once successful, you'll return to our site automatically.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fixed Email and Phone Fields -->
                    <div style="margin-bottom: 20px;">
                        <div style="background-color: #f9f9f9; border-radius: 10px; padding: 20px; border-left: 4px solid #4a6bff">
                            <h4 style="margin: 0 0 15px; font-size: 16px; color: #333;">Contact Information for Payment Receipt</h4>
                            
                            <!-- Email Field -->
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="payhere_email" style="display: block; margin-bottom: 8px; font-size: 14px; color: #555; font-weight: 500;">
                                    <i class="far fa-envelope" style="color: #4a6bff; margin-right: 8px;"></i> Email Address*
                                </label>
                                <input type="email" id="payhere_email" placeholder="email@example.com" 
                                    value="<?= $_SESSION['user']->email ?? '' ?>" 
                                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 10px; font-size: 15px; outline: none;">
                                <small id="email_message" style="display:none;color:#dc3545;">Please enter a valid email address.</small>
                            </div>
                            
                            <!-- Phone Field -->
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="payhere_phone" style="display: block; margin-bottom: 8px; font-size: 14px; color: #555; font-weight: 500;">
                                    <i class="fas fa-phone" style="color: #4a6bff; margin-right: 8px;"></i> Phone Number*
                                </label>
                                <input type="tel" id="payhere_phone" placeholder="+94 XX XXX XXXX" 
                                    value="<?= $_SESSION['user']->phone ?? '' ?>" 
                                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 10px; font-size: 15px; outline: none;">
                                <small id="phone_message" style="display:none;color:#dc3545;">Please enter a valid phone number.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credit Card Form -->
                <div id="credit_card_form" class="payment_form" style="display: none;">
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
                
                <!-- Payment button -->
                <div style="margin-top: 30px; text-align: center;">
                    <button type="button" style="padding: 15px 40px; background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%); color: white; border: none; border-radius: 30px; font-size: 16px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2);" onclick="processPayment()">
                        Pay LKR <?= number_format($total_amount, 2) ?>
                    </button>
                    <!-- Add this near your payment button temporarily -->
<div style="margin-top: 10px; text-align: center;">
    <button type="button" style="padding: 8px 15px; background: #f8f9fa; color: #555; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; font-size: 13px;" onclick="testAPIConnection()">
        Test API Connection
    </button>
</div>
                </div>
            </form>
        </div>
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
        <div style="width: 80px; height: 80px; margin: 0 auto 20px; background-color: #e8f0fe; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-check" style="font-size: 40px; color: #4a6bff;"></i>
        </div>
        <h2 style="color: #333; margin-bottom: 15px;">Payment Successful!</h2>
        <p style="color: #666; margin-bottom: 25px; font-size: 16px;">Your payment of LKR <?= number_format($total_amount, 2) ?> has been processed successfully.</p>
        <div style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; display: inline-block;">
            <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Transaction ID</div>
            <div style="font-size: 16px; font-weight: 600; color: #333;"><?= 'TXN-'.strtoupper(substr(md5(time()), 0, 10)) ?></div>
        </div>
        <div>
            <a href="<?= ROOT ?>/dashboard/externalMaintenance" style="display: inline-block; padding: 12px 25px; background-color: #4a6bff; color: white; text-decoration: none; border-radius: 30px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#3551cc';" onmouseout="this.style.backgroundColor='#4a6bff';">
                Back to Maintenance
            </a>
        </div>
    </div>
</div>

<!-- Payment Processing Indicator -->
<div id="payment-processing-indicator" style="display: none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(255,255,255,0.8); align-items: center; justify-content: center; flex-direction: column;">
    <div style="width: 60px; height: 60px; border: 5px solid #f3f3f3; border-top: 5px solid #4a6bff; border-radius: 50%; animation: spin 1s linear infinite;"></div>
    <p style="margin-top: 20px; font-size: 16px; color: #333; font-weight: 500;">Processing Payment...</p>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
@keyframes zoomIn {
    from {transform: scale(0.1); opacity: 0;}
    to {transform: scale(1); opacity: 1;}
}
</style>

<script>
    function selectPaymentMethod(element, method) {
        document.querySelectorAll('.payment-option').forEach(option => {
            option.style.backgroundColor = '#f8f9fa';
            option.style.borderColor = '#ddd';
        });
        element.style.backgroundColor = '#e8f0fe';
        element.style.borderColor = '#4a6bff';
        document.querySelectorAll('.payment_form').forEach(form => {
            form.style.display = 'none';
        });
        document.getElementById(method + '_form').style.display = 'block';
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function showErrorMessage(message) {
        const errorContainer = document.createElement('div');
        errorContainer.className = 'payment-error-container';
        errorContainer.style.position = 'fixed';
        errorContainer.style.top = '20%';
        errorContainer.style.left = '50%';
        errorContainer.style.transform = 'translate(-50%, -50%)';
        errorContainer.style.backgroundColor = '#fff';
        errorContainer.style.padding = '25px';
        errorContainer.style.borderRadius = '10px';
        errorContainer.style.boxShadow = '0 5px 25px rgba(0,0,0,0.2)';
        errorContainer.style.zIndex = '9999';
        errorContainer.style.maxWidth = '450px';
        errorContainer.style.width = '90%';
        errorContainer.innerHTML = `
            <div style="text-align: center;">
                <div style="color: #e74c3c; font-size: 48px; margin-bottom: 15px;">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h3 style="margin: 0 0 15px; color: #333; font-size: 20px;">Payment Gateway Error</h3>
                <p style="margin: 0 0 20px; color: #666; font-size: 15px; line-height: 1.5;">${message}</p>
                <p style="color: #666; font-size: 14px; margin: 0 0 15px;">
                    Possible reasons:
                </p>
                <ul style="text-align: left; margin: 0 0 20px; padding-left: 20px; color: #666; font-size: 14px;">
                    <li style="margin-bottom: 5px;">Network connectivity issues</li>
                    <li style="margin-bottom: 5px;">Server timeout</li>
                    <li style="margin-bottom: 5px;">Payment gateway maintenance</li>
                </ul>
                <button onclick="retryPayment()" style="background-color: #4a6bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-right: 10px; font-weight: 500;">Try Again</button>
                <button onclick="this.parentElement.parentElement.remove()" style="background-color: transparent; border: 1px solid #ddd; color: #666; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: 500;">Close</button>
            </div>
        `;
        document.body.appendChild(errorContainer);
    }

    function retryPayment() {
        document.querySelector('.payment-error-container').remove();
        processPayment();
    }

    function processPayment() {
        const email = document.getElementById('payhere_email').value;
        const phone = document.getElementById('payhere_phone').value;
        const emailMessage = document.getElementById('email_message');
        const phoneMessage = document.getElementById('phone_message');
        let isValid = true;

        if (!email || !validateEmail(email)) {
            document.getElementById('payhere_email').style.borderColor = '#dc3545';
            emailMessage.style.display = 'block';
            isValid = false;
        } else {
            document.getElementById('payhere_email').style.borderColor = '#4CAF50';
            emailMessage.style.display = 'none';
        }

        if (!phone || phone.replace(/\D/g, '').length < 10) {
            document.getElementById('payhere_phone').style.borderColor = '#dc3545';
            phoneMessage.style.display = 'block';
            isValid = false;
        } else {
            document.getElementById('payhere_phone').style.borderColor = '#4CAF50';
            phoneMessage.style.display = 'none';
        }

        if (!isValid) return;
        document.getElementById('payment-processing-indicator').style.display = 'flex';
        startPayHerePayment(email, phone);
    }

    function startPayHerePayment(email, phone) {
        fetch('<?= ROOT ?>/payment/api/prepare', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-cache'
            },
            body: JSON.stringify({
                email: email,
                phone: phone,
                service_id: '<?= $service->id ?>',
                timestamp: new Date().getTime()
            })
        })
        .then(response => response.text())
        .then(text => {
            if (text.trim().startsWith('<!DOCTYPE') || text.trim().startsWith('<html')) {
                throw new Error('Received HTML instead of JSON - using alternate payment method');
            }
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error('Invalid JSON response from server');
            }
        })
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Unknown error preparing payment');
            createPayHereForm(data);
        })
        .catch(error => {
            document.getElementById('payment-processing-indicator').style.display = 'none';
            showErrorMessage(error.message);
        });
    }

    function createPayHereForm(data) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = 'https://sandbox.payhere.lk/pay/checkout';
        const params = {
            merchant_id: data.merchant_id,
            return_url: data.return_url,
            cancel_url: data.cancel_url,
            notify_url: data.notify_url,
            order_id: data.order_id,
            items: 'External Service Payment',
            amount: data.amount,
            currency: data.currency,
            first_name: data.first_name,
            last_name: data.last_name,
            email: data.email,
            phone: data.phone,
            address: data.address,
            city: data.city,
            country: data.country
        };
        if (data.delivery_address) params.delivery_address = data.delivery_address;
        if (data.delivery_city) params.delivery_city = data.delivery_city;
        if (data.delivery_country) params.delivery_country = data.delivery_country;
        if (data.service_id) params.custom_1 = data.service_id;
        if (data.user_id) params.custom_2 = data.user_id;
        for (const key in params) {
            if (params[key] !== undefined && params[key] !== null) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = params[key];
                form.appendChild(input);
            }
        }
        document.body.appendChild(form);
        setTimeout(() => form.submit(), 500);
    }

    // Phone formatting on blur
    document.addEventListener('DOMContentLoaded', function() {
        const phoneField = document.getElementById('payhere_phone');
        if (phoneField) {
            phoneField.addEventListener('blur', function() {
                let digits = this.value.replace(/\D/g, '');
                if (!digits.startsWith('94')) digits = '94' + digits;
                let formatted = '+' + digits.substring(0, 2) + ' ';
                if (digits.length > 2) formatted += digits.substring(2);
                this.value = formatted;
            });
        }
    });

    // API test function
    function testAPIConnection() {
        fetch('<?= ROOT ?>/payment/api/test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                alert("API connection successful! " + data.message);
            } catch (e) {
                alert("API test failed: Could not parse JSON");
            }
        })
        .catch(error => {
            alert("API connection test failed: " + error.message);
        });
    }
    
    function scrollImages(direction) {
        const container = document.querySelector('.service-image-slider');
        const scrollAmount = 180; // Width of one image + gap
        
        if (direction === 'left') {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
    
    function scrollRequestImages(direction) {
        const container = document.querySelector('.request-image-slider');
        const scrollAmount = 155; // Width of one image + gap
        
        if (direction === 'left') {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
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

    // Add this to your existing script section
    document.addEventListener('DOMContentLoaded', function() {
        const emailField = document.getElementById('payhere_email');
        const phoneField = document.getElementById('payhere_phone');
        const emailMessage = document.getElementById('email_message');
        const phoneMessage = document.getElementById('phone_message');
        
        // Email validation
        emailField.addEventListener('input', function() {
            if (validateEmail(this.value)) {
                emailField.style.borderColor = '#4CAF50';
                emailMessage.style.display = 'none';
            } else {
                if (this.value.length > 0) {
                    emailField.style.borderColor = '#dc3545';
                    emailMessage.style.display = 'block';
                } else {
                    emailField.style.borderColor = '#ddd';
                    emailMessage.style.display = 'none';
                }
            }
        });
        
        // Phone validation
        phoneField.addEventListener('input', function() {
            // Simple validation - just check if there are at least 10 digits
            let digits = this.value.replace(/\D/g, '');
            if (digits.length >= 10) {
                phoneField.style.borderColor = '#4CAF50';
                if (phoneMessage) phoneMessage.style.display = 'none';
            } else {
                if (this.value.length > 0) {
                    phoneField.style.borderColor = '#dc3545';
                    if (phoneMessage) phoneMessage.style.display = 'block';
                } else {
                    phoneField.style.borderColor = '#ddd';
                    if (phoneMessage) phoneMessage.style.display = 'none';
                }
            }
        });
        
        
        function formatPhoneOnBlur() {
            const phoneField = document.getElementById('payhere_phone');
            if (!phoneField) return;
            
            phoneField.addEventListener('blur', function() {
                // Only format if not already formatted
                if (!this.value.startsWith('+94 ')) {
                    let digits = this.value.replace(/\D/g, '');
                    // If the number doesn't start with 94, prepend it
                    if (!digits.startsWith('94')) {
                        digits = '94' + digits;
                    }
                    
                    // Format the number
                    let formatted = '+' + digits.substring(0, 2) + ' ';
                    
                    if (digits.length > 2) {
                        formatted += digits.substring(2);
                    }
                    
                    this.value = formatted;
                }
            });
        }

        // Call this function when the document is ready
        document.addEventListener('DOMContentLoaded', function() {
            formatPhoneOnBlur();
            // ...other code
        });
        
        // Trigger validation on page load if fields have values
        if (emailField.value) {
            emailField.dispatchEvent(new Event('input'));
        }
        if (phoneField.value) {
            phoneField.dispatchEvent(new Event('input'));
        }
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Add this to your scripts in payExternalService.view.php
    function startPayHerePayment(email, phone) {
        console.log("Starting payment process using direct API...");
        document.getElementById('payment-processing-indicator').style.display = 'flex';
        
        // Use the Payment controller's API endpoint instead
        fetch('<?= ROOT ?>/payment/api/prepare', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-cache'
            },
            body: JSON.stringify({
                email: email,
                phone: phone,
                service_id: '<?= $service->id ?>',
                timestamp: new Date().getTime() // Prevent caching
            })
        })
        .then(response => {
            console.log("API Response status:", response.status);
            return response.text();
        })
        .then(text => {
            console.log("API Raw response:", text.substring(0, 200)); // Show first 200 chars
            
            // Check if response is HTML
            if (text.trim().startsWith('<!DOCTYPE') || text.trim().startsWith('<html')) {
                throw new Error('Received HTML instead of JSON - using alternate payment method');
            }
            
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error("JSON parse error:", e);
                throw new Error('Invalid JSON response from server');
            }
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Unknown error preparing payment');
            }
            
            // Create PayHere form
            createPayHereForm(data);
        })
        .catch(error => {
            console.error("Payment error:", error);
            document.getElementById('payment-processing-indicator').style.display = 'none';
            
            // If API fails, fall back to direct payment
            if (confirm("Payment gateway connection failed. Would you like to try direct bank transfer payment instead?")) {
                selectPaymentMethod(document.querySelector('label[onclick*="bank_transfer"]'), 'bank_transfer');
            } else {
                showErrorMessage(error.message);
            }
        });
    }

    // Add a test function
    function testAPIConnection() {
        fetch('<?= ROOT ?>/payment/api/test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(text => {
            console.log("Test response:", text);
            try {
                const data = JSON.parse(text);
                alert("API connection successful! " + data.message);
            } catch (e) {
                alert("API test failed: Could not parse JSON");
            }
        })
        .catch(error => {
            console.error("API test error:", error);
            alert("API connection test failed: " + error.message);
        });
    }
 
</script>

<?php require_once 'customerFooter.view.php'; ?>