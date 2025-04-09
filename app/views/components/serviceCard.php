<div style="background-color: #fff; border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; width: 100%; display: flex; margin-bottom: 0;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0, 0, 0, 0.12)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.08)';">
    <!-- Left Section (Content) -->
    <div style="flex: 1; overflow: hidden;">
        <!-- Card Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 25px; background-color: #f8f9fa; border-bottom: 1px solid #eaeaea;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #333;"><?= $serviceLog->service_type ?? 'Unknown Service' ?></h3>
            <?php 
                $statusColor = '';
                $statusTextColor = '';
                $statusGradient = '';
                $statusIcon = '';
                $status = strtolower($serviceLog->status ?? 'pending');
                
                switch($status) {
                    case 'pending':
                        $statusGradient = 'linear-gradient(135deg, #FFA726 0%, #FB8C00 100%)';
                        $statusTextColor = '#fff';
                        $statusIcon = 'fa-hourglass-half';
                        break;
                    case 'in-progress':
                    case 'in progress':
                        $statusGradient = 'linear-gradient(135deg, #29B6F6 0%, #0288D1 100%)';
                        $statusTextColor = '#fff';
                        $statusIcon = 'fa-spinner fa-spin';
                        break;
                    case 'done':
                        $statusGradient = 'linear-gradient(135deg, #66BB6A 0%, #388E3C 100%)';
                        $statusTextColor = '#fff';
                        $statusIcon = 'fa-check-circle';
                        break;
                    case 'cancelled':
                        $statusGradient = 'linear-gradient(135deg, #EF5350 0%, #D32F2F 100%)';
                        $statusTextColor = '#fff';
                        $statusIcon = 'fa-times-circle';
                        break;
                    case 'paid':
                        $statusGradient = 'linear-gradient(135deg, #AB47BC 0%, #7B1FA2 100%)';
                        $statusTextColor = '#fff';
                        $statusIcon = 'fa-credit-card';
                        break;
                    default:
                        $statusGradient = 'linear-gradient(135deg, #FFA726 0%, #FB8C00 100%)';
                        $statusTextColor = '#fff';
                        $statusIcon = 'fa-hourglass-half';
                }
            ?>
            <span style="padding: 8px 16px; border-radius: 30px; font-size: 14px; font-weight: 600; text-transform: capitalize; background: <?= $statusGradient ?>; color: <?= $statusTextColor ?>; box-shadow: 0 3px 5px rgba(0,0,0,0.1); display: flex; align-items: center;">
                <i class="fas <?= $statusIcon ?>" style="margin-right: 6px;"></i>
                <?= $serviceLog->status ?? 'Pending' ?>
            </span>
        </div>
        
        <!-- Card Content -->
        <div style="padding: 20px 25px;">
            <!-- Service ID and Price Row -->
            <div style="display: flex; flex-wrap: wrap; margin-bottom: 15px;">
                <div style="flex: 1; min-width: 250px; margin-right: 20px; margin-bottom: 10px; background-color: #f9f9f9; padding: 12px 15px; border-radius: 8px;">
                    <label style="font-weight: 600; color: #555; margin-right: 8px; font-size: 14px;">Service ID:</label>
                    <span style="color: #333; font-size: 15px;"><?= $serviceLog->service_id ?? 'N/A' ?></span>
                </div>
                <div style="flex: 1; min-width: 250px; margin-bottom: 10px; background-color: #f9f9f9; padding: 12px 15px; border-radius: 8px;">
                    <label style="font-weight: 600; color: #555; margin-right: 8px; font-size: 14px;">Price:</label>
                    <span style="color: #2c7be5; font-weight: 700; font-size: 18px;"><?= number_format(($serviceLog->cost_per_hour ?? 0) * ($serviceLog->total_hours ?? 0), 2) ?> LKR</span>
                </div>
            </div>
            
            <!-- Date and Time Row -->
            <div style="display: flex; flex-wrap: wrap; margin-bottom: 15px;">
                <div style="flex: 1; min-width: 250px; margin-right: 20px; margin-bottom: 10px; background-color: #f9f9f9; padding: 12px 15px; border-radius: 8px;">
                    <label style="font-weight: 600; color: #555; margin-right: 8px; font-size: 14px;">Date:</label>
                    <span style="color: #333; font-size: 15px;"><?= isset($serviceLog->date) ? date('Y/m/d', strtotime($serviceLog->date)) : 'N/A' ?></span>
                </div>
                <div style="flex: 1; min-width: 250px; margin-bottom: 10px; background-color: #f9f9f9; padding: 12px 15px; border-radius: 8px;">
                    <label style="font-weight: 600; color: #555; margin-right: 8px; font-size: 14px;">Time:</label>
                    <span style="color: #333; font-size: 15px;"><?= isset($serviceLog->date) ? date('h:i A', strtotime($serviceLog->date)) : 'N/A' ?></span>
                </div>
            </div>
            
            <!-- Provider and Property Row -->
            <div style="display: flex; flex-wrap: wrap; margin-bottom: 15px;">
                <div style="flex: 1; min-width: 250px; margin-right: 20px; margin-bottom: 10px; background-color: #f9f9f9; padding: 12px 15px; border-radius: 8px;">
                    <label style="font-weight: 600; color: #555; margin-right: 8px; font-size: 14px;">Provider:</label>
                    <span style="color: #333; font-size: 15px;"><?= $serviceLog->service_provider_id ? "Provider ID: " . $serviceLog->service_provider_id : 'Not assigned' ?></span>
                </div>
                <div style="flex: 1; min-width: 250px; margin-bottom: 10px; background-color: #f9f9f9; padding: 12px 15px; border-radius: 8px;">
                    <label style="font-weight: 600; color: #555; margin-right: 8px; font-size: 14px;">Property:</label>
                    <span style="color: #333; font-size: 15px;"><?= $serviceLog->property_name ?? 'Unknown Property' ?></span>
                </div>
            </div>
            
            <!-- Description -->
            <div style="margin-bottom: 15px; background-color: #f9f9f9; padding: 12px 15px; border-radius: 8px;">
                <label style="font-weight: 600; color: #555; margin-bottom: 5px; display: block; font-size: 14px;">Description:</label>
                <div style="color: #333; font-size: 15px;"><?= $serviceLog->service_description ?? 'No description provided' ?></div>
            </div>
            
            <!-- Provider Notes -->
            <?php if (!empty($serviceLog->service_provider_description)): ?>
            <div style="margin-bottom: 15px; background-color: #f9f9f9; padding: 12px 15px; border-radius: 8px;">
                <label style="font-weight: 600; color: #555; margin-bottom: 5px; display: block; font-size: 14px;">Provider Notes:</label>
                <div style="color: #333; font-size: 15px;"><?= $serviceLog->service_provider_description ?></div>
            </div>
            <?php endif; ?>
            
            <!-- Payment Button -->
            <?php if (strtolower($serviceLog->status ?? '') === 'done'): ?>
            <div style="margin-top: 15px; display: flex; justify-content: flex-end; padding-top: 15px; border-top: 1px solid #eee;">
                <a href="<?= ROOT ?>dashboard/payment/<?= $serviceLog->service_id ?>" style="display: inline-flex; align-items: center; background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%); color: white; padding: 12px 25px; border-radius: 30px; text-decoration: none; transition: all 0.2s; font-weight: 600; box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2);" onmouseover="this.style.backgroundColor='#218838'; this.style.color='white'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 8px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 6px rgba(40, 167, 69, 0.2)';">
                    Proceed to Payment <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>