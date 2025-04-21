<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <!-- <a href="<?= ROOT ?>/property/"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a> -->
            <a href="javascript:history.back()"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2>Property name</h2>
                <p><span>Maintained By: </span><?= $agentName ?></p>
            </div>
        </div>
    </div>
</div>
<div class="RP__container" style="width: auto;">
    <div class="RP_side-containers">
        <h2>Report your Problem</h2>
        <img src="<?= ROOT ?>/assets/images/reportform.jpg" alt="Report about property">
    </div>
    <div class="RP_side-containers">
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="urgency">How urgent is the problem?</label>
                <select name="urgency" id="urgency" class="input-field" required>
                    <option value="1">Low</option>
                    <option value="2">Medium</option>
                    <option value="3">Urgent</option>
                </select>
            </div>
            <div>
                <label for="location">Where is the problem?</label>
                <input type="text" name="location" id="location" class="input-field" required>
            </div>
            <div>
                <label for="problem">What is the problem?</label>
                <textarea name="problem" id="problem" cols="30" rows="3" class="input-field" required></textarea>
            </div>

            <label> Upload Images (Max 6)*</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="reports_images[]" id="reports_images" class="input-field" multiple
                    accept=".png, .jpg, .jpeg" data-max-files="6" onchange="handleFileSelect(event)">
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('reports_images').click()">Choose File</button>
                </div>
            </div>
            <div id="preview-container" class="owner-addProp-uploaded-files">
                <!-- Preview area for selected images -->
            </div>
            <div class="to-flex-end">
                <button type="submit" class="primary-btn" style="align-self: flex-end;">Submit</button>
            </div>
        </form>
    </div>
</div>
<?php if (!empty($reports)): ?>
    <h3 style="margin: 20px 0 20px 20px;">Previous Problem Reports</h3>
    <div>
        <?php foreach ($reports as $report): ?>
            <div class="" style="position:relative; margin:0 15px 16px 6px; box-shadow:0 2px 8px rgba(0,0,0,0.08); display:flex; flex-direction:column; gap:6px; border-radius:8px; padding:18px 22px 30px 22px; background:#fff;">
                <!-- Status badge at top right -->
                <?php
                    $status = strtolower($report['status'] ?? 'pending');
                    $statusGradient = '';
                    $statusTextColor = '';
                    $statusIcon = '';
                    switch($status) {
                        case 'pending':
                            $statusGradient = 'linear-gradient(135deg, #FFA726 0%, #FB8C00 100%)';
                            $statusTextColor = '#fff';
                            $statusIcon = 'fa-hourglass-half';
                            $badgeText = 'Pending';
                            break;
                        case 'in_progress':
                            $statusGradient = 'linear-gradient(135deg, #29B6F6 0%, #0288D1 100%)';
                            $statusTextColor = '#fff';
                            $statusIcon = 'fa-spinner fa-spin';
                            $badgeText = 'In Progress';
                            break;
                        case 'resolved':
                            $statusGradient = 'linear-gradient(135deg, #66BB6A 0%, #388E3C 100%)';
                            $statusTextColor = '#fff';
                            $statusIcon = 'fa-check-circle';
                            $badgeText = 'Resolved';
                            break;
                    }
                ?>
                
                <span style="position:absolute; top:18px; right:22px; padding: 8px 16px; border-radius: 30px; font-size: 14px; font-weight: 600; text-transform: capitalize; background: <?= $statusGradient ?>; color: <?= $statusTextColor ?>; box-shadow: 0 3px 5px rgba(0,0,0,0.1); display: flex; align-items: center;">
                    <i class="fas <?= $statusIcon ?>" style="margin-right: 6px;"></i>
                    <?= $report['status'] ?? 'Pending' ?>
                </span>
                <span style="position:absolute; bottom:8px; right:12px; font-size:12px; color: var(--primary-color);">
                    <?= htmlspecialchars($report['submitted_at']) ?>
                </span>

                <div style="margin-top:4px;"><b style="margin-top:4px; color:<?= $statusGradient ?>;">Urgency:</b> <?= htmlspecialchars($report['urgency_label']) ?></div>
                <div style="margin-top:8px;">
                    <b>Images:</b>
                    <div style="margin-top:4px; display:flex; gap:6px;">
                    <?php
                        $maxImages = 6;
                        $images = !empty($report['images']) ? $report['images'] : [];
                        $count = count($images);
                    ?>
                    <?php for ($i = 0; $i < $maxImages; $i++): ?>
                        <?php if ($i < $count): ?>
                        <div style="width:60px; height:80px; display:flex; align-items:center; justify-content:center; background:#fafafa; border-radius:4px; border:1px solid #eee;">
                            <img src="<?= ROOT ?>/assets/images/uploads/report_images/<?= htmlspecialchars($images[$i]) ?>" alt="Report Image" width="56" height="56" style="object-fit:cover; border-radius:3px;">
                        </div>
                        <?php else: ?>
                        <div style="width:60px; height:60px; display:flex; align-items:center; justify-content:center; background:#fafafa; border-radius:4px; border:1px solid #eee; color:#bbb; font-size:12px;">
                            No Image
                        </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                    </div>
                </div>
                <div style="margin-top:8px; display:flex; flex-direction:column;">
                    <b>Description:</b>
                    <textarea readonly style="margin-top:4px; border:1px solid #ccc; border-radius:4px; padding:8px 12px; background:#f9f9f9; font-size:15px; color:#444; width:100%; max-width:100%; min-height:60px; resize:vertical; word-break:break-word; box-sizing:border-box;"><?= htmlspecialchars($report['problem_description']) ?></textarea>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<script>
    const ROOT = '<?= ROOT ?>';
</script>
<script src="<?= ROOT ?>/assets/js/reports/addreports.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<?php require_once 'ownerFooter.view.php'; ?>