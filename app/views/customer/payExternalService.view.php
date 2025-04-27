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
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= htmlspecialchars($service->service_type ?? 'Unknown Service') ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service ID</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;">#<?= $service->id ?? 'N/A' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Address</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= htmlspecialchars($service->property_address ?? 'Unknown Location') ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Date</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= isset($service->date) ? date('Y/m/d', strtotime($service->date)) : 'N/A' ?></div>
                </div>
                <?php if (isset($service->service_provider_id) && !empty($service->service_provider_id)): ?>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Provider</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;">
                        <?php
                        $userModel = new User();
                        $provider = $userModel->first(['pid' => $service->service_provider_id]);
                        echo htmlspecialchars(trim(($provider->fname ?? '') . ' ' . ($provider->lname ?? ''))) ?: 'N/A';
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Status</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;">
                        <span class="status-badge status-<?= strtolower($service->status ?? 'unknown') ?>">
                            <span class="status-dot"></span>
                            <?= ucfirst($service->status ?? 'Unknown') ?>
                        </span>
                    </div>
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
                        <div style="font-size: 13px; color: #888;"><?= $service->total_hours ?? 0 ?> hours × LKR <?= number_format($service->cost_per_hour ?? 0, 2) ?></div>
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

        <!-- Change this section where PayHere variables are defined -->
        <?php
        $merchant_id = MERCHANT_ID;
        $merchant_secret = MERCHANT_SECRET;
        $order_id = uniqid("EXT_SERVICE_");
        $_SESSION['order_id'] = $order_id; // Store order ID in session for later use
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

        // Define PayHere URL - THIS WAS MISSING
        $payhere_url = 'https://sandbox.payhere.lk/pay/checkout';

        // Get property details for address if available
        $property_address = '';
        $property_city = '';

        if (isset($service->property_id)) {
            $propertyModel = new PropertyConcat();
            $property = $propertyModel->first(['property_id' => $service->property_id]);
            if ($property) {
                $property_address = $property->address ?? '';
                $property_city = $property->city ?? '';
            }
        }
        ?>

        <!-- Payment Form -->
        <div style="padding: 25px 30px;">
            <h3 style="margin: 0 0 20px; font-size: 18px; color: #444; display: flex; align-items: center;">
                <i class="fas fa-credit-card" style="margin-right: 10px; color: #4a6bff;"></i> Complete Payment
            </h3>

            <form id="payhereForm" method="post" action="<?= $payhere_url ?>" style="padding: 25px 30px; background-color: #f9fafc; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <input type="hidden" name="merchant_id" value="<?= $merchant_id ?>">
                <!-- FIX: Change dashboard to customer in URLs -->
                <input type="hidden" name="return_url" value="<?= ROOT ?>/dashboard/externalServicePayment/success?order_id=<?= $order_id ?>&amount=<?= $amount_formatted ?>&service_id=<?= $service->id ?>">
                <input type="hidden" name="cancel_url" value="<?= ROOT ?>/dashboard/externalServicePayment/cancel?order_id=<?= $order_id ?>&amount=<?= $amount_formatted ?>&service_id=<?= $service->id ?>">
                <input type="hidden" name="notify_url" value="<?= ROOT ?>/dashboard/externalServicePayment/notify">
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <input type="hidden" name="items" value="External Service Payment for Service #<?= $service->id ?>">
                <input type="hidden" name="currency" value="<?= $currency ?>">
                <input type="hidden" name="amount" value="<?= $amount_formatted ?>">
                <input type="hidden" name="hash" value="<?= $hash ?>">
                <input type="hidden" name="first_name" value="<?= htmlspecialchars($_SESSION['user']->fname ?? '') ?>">
                <input type="hidden" name="last_name" value="<?= htmlspecialchars($_SESSION['user']->lname ?? '') ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['user']->email ?? '') ?>">
                <input type="hidden" name="phone" value="<?= htmlspecialchars($_SESSION['user']->phone ?? $_SESSION['user']->contact ?? '') ?>">
                <input type="hidden" name="address" value="<?= htmlspecialchars($service->property_address ?? '') ?>">
                <input type="hidden" name="city" value="<?= htmlspecialchars($service->property_city ?? '') ?>">
                <input type="hidden" name="country" value="Sri Lanka">
                
                
                <div style="margin-top: 30px; text-align: center;">
                    <button type="submit" style="padding: 15px 40px; background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%); color: white; border: none; border-radius: 30px; font-size: 16px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 12px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 6px rgba(40, 167, 69, 0.2)';">
                        Pay LKR <?= number_format($total_amount, 2) ?>
                    </button>
                    <p style="margin-top: 15px; font-size: 13px; color: #777;">You will be redirected to secure payment gateway</p>
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

<script>
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

    // Add zoom-in animation
    document.head.insertAdjacentHTML('beforeend', `
        <style>
            @keyframes zoomIn {
                from {transform: scale(0.1); opacity: 0;}
                to {transform: scale(1); opacity: 1;}
            }
            
            /* Status badge styling */
            .status-badge {
                display: inline-flex;
                align-items: center;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .status-dot {
                display: inline-block;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                margin-right: 6px;
            }
            
            /* Status colors */
            .status-pending {
                background-color: #FFF8E1;
                color: #F57C00;
            }
            
            .status-pending .status-dot {
                background-color: #F57C00;
                box-shadow: 0 0 0 2px rgba(245, 124, 0, 0.2);
            }
            
            .status-ongoing {
                background-color: #E3F2FD;
                color: #1976D2;
            }
            
            .status-ongoing .status-dot {
                background-color: #1976D2;
                box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.2);
            }
            
            .status-done {
                background-color: #E8F5E9;
                color: #388E3C;
            }
            
            .status-done .status-dot {
                background-color: #388E3C;
                box-shadow: 0 0 0 2px rgba(56, 142, 60, 0.2);
            }
            
            .status-paid {
                background-color: #E8F5E9;
                color: #388E3C;
            }
            
            .status-paid .status-dot {
                background-color: #388E3C;
                box-shadow: 0 0 0 2px rgba(56, 142, 60, 0.2);
            }
            
            .status-rejected, .status-cancelled {
                background-color: #FFEBEE;
                color: #D32F2F;
            }
            
            .status-rejected .status-dot, .status-cancelled .status-dot {
                background-color: #D32F2F;
                box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.2);
            }
            
            .status-unknown {
                background-color: #ECEFF1;
                color: #607D8B;
            }
            
            .status-unknown .status-dot {
                background-color: #607D8B;
                box-shadow: 0 0 0 2px rgba(96, 125, 139, 0.2);
            }
        </style>
    `);
</script>

<?php require_once 'customerFooter.view.php'; ?>