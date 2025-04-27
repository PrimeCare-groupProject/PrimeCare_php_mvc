<?php require_once 'customerHeader.view.php'; ?>

<div class="user_view-menu-bar" style="margin-bottom: 20px;">
    <div class="gap"></div>
    <h2>Service Payment</h2>
    <div class="flex-bar">
        <a href="<?= ROOT ?>/dashboard/serviceRequests" style="display: inline-flex; align-items: center; text-decoration: none; color: #666; font-size: 14px;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back to Service Requests
        </a>
    </div>
</div>

<div style="max-width: 900px; margin: 0 auto; padding: 0 20px 40px;">
    <div style="background-color: #fff; border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); overflow: hidden; margin-bottom: 30px;">
        <!-- Payment Header -->
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
        $service_images = !empty($service->service_images) ? json_decode($service->service_images) : [];
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
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $service->service_type ?? 'Unknown Service' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service ID</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $service->service_id ?? 'N/A' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Property</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= $service->property_name ?? 'Unknown Property' ?></div>
                </div>
                <div>
                    <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Date</div>
                    <div style="font-size: 16px; font-weight: 600; color: #333;"><?= isset($service->date) ? date('Y/m/d', strtotime($service->date)) : 'N/A' ?></div>
                </div>
            </div>

            <!-- Service Description Section -->
            <?php if (!empty($service->service_description) || !empty($service->service_provider_description)): ?>
                <div style="margin-top: 20px; background-color: #f9f9f9; padding: 15px; border-radius: 10px; border-left: 4px solid #4a6bff;">
                    <?php if (!empty($service->service_description)): ?>
                        <div style="margin-bottom: 15px;">
                            <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Request Description</div>
                            <div style="font-size: 15px; color: #444; line-height: 1.5;"><?= nl2br(htmlspecialchars($service->service_description)) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($service->service_provider_description)): ?>
                        <div>
                            <div style="font-size: 13px; text-transform: uppercase; color: #888; margin-bottom: 5px;">Service Provider Notes</div>
                            <div style="font-size: 15px; color: #444; line-height: 1.5;"><?= nl2br(htmlspecialchars($service->service_provider_description)) ?></div>
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
                        <div style="font-size: 15px; font-weight: 500; color: #555;">Service Cost</div>
                    </div>
                    <div style="font-size: 15px; font-weight: 600; color: #333;">LKR <?= number_format($service_cost, 2) ?></div>
                </div>

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

        <?php
        $merchant_id = MERCHANT_ID;
        $merchant_secret = MERCHANT_SECRET;
        $order_id = uniqid("SERVICE_");
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
        <form method="post" action="https://sandbox.payhere.lk/pay/checkout" style="padding: 25px 30px;">
            <input type="hidden" name="merchant_id" value="<?= $merchant_id ?>">
            <input type="hidden" name="return_url" value="http://localhost/php_mvc_backend/public/dashboard/servicePayment/success?order_id=<?= $order_id ?>&amount=<?= $amount_formatted ?>&service_id=<?= $service->service_id ?>">
            <input type="hidden" name="cancel_url" value="http://localhost/php_mvc_backend/public/ashboard/servicePayment/cancel?order_id=<?= $order_id ?>&amount=<?= $amount_formatted ?>&service_id=<?= $service->service_id ?>">
            <input type="hidden" name="notify_url" value="http://localhost/php_mvc_backend/public/ashboard/servicePayment/notify">
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <input type="hidden" name="items" value="Service Payment">
            <input type="hidden" name="currency" value="<?= $currency ?>">
            <input type="hidden" name="amount" value="<?= $amount_formatted ?>">
            <input type="hidden" name="hash" value="<?= $hash ?>">
            <input type="hidden" name="first_name" value="<?= $_SESSION['user']->fname ?? '' ?>">
            <input type="hidden" name="last_name" value="<?= $_SESSION['user']->lname ?? '' ?>">
            <input type="hidden" name="email" value="<?= $_SESSION['user']->email ?? '' ?>">
            <input type="hidden" name="phone" value="<?= $_SESSION['user']->contact ?? '' ?>">
            <input type="hidden" name="address" value="<?= $property_address ?>">
            <input type="hidden" name="city" value="<?= $property_city ?>">
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

<script>
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

<?php require_once 'customerFooter.view.php'; ?>