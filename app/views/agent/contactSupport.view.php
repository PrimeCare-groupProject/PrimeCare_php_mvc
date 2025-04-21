<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Contact Support</h2>
    <div class="flex-bar">
    <form class="search-container" method="GET">
            <input 
                type="text" 
                class="search-input" 
                name="search" 
                value="<?= isset($_GET['search']) ? esc($_GET['search']) : "" ?>" 
                placeholder="Search Provider ..."
            >
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
    </div>
</div>

<?php if (!empty($reports)): ?>
    <div>
        <?php foreach ($reports as $idx => $report): ?>
            <div class="inventory-details-container" style="position:relative; padding-bottom: 22px;">
                <!-- Property Name -->
                <a href="<?= ROOT ?>/dashboard/contactsupport/propertyunit/<?= esc($report['property_id']) ?>" style="text-decoration:none;">
                    <h2 style="font-weight:500; color:#2a4d8f; margin:4px 0 6px 0;">
                        <?= esc($report['property_name'] ?? 'Unknown Property') ?>
                    </h2>
                </a>
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
                <span style="position:absolute; top:18px; right:22px; padding: 8px 16px; border-radius: 30px; font-size: 14px; font-weight: 500; text-transform: capitalize; background: <?= $statusGradient ?>; color: <?= $statusTextColor ?>; box-shadow: 0 3px 5px rgba(0,0,0,0.1); display: flex; align-items: center;">
                    <i class="fas <?= $statusIcon ?>" style="margin-right: 6px;"></i>
                    <?= $report['status'] ?? 'Pending' ?>
                </span>
                <span style="position:absolute; bottom:6px; right:10px; font-size:12px; color: var(--primary-color);">
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
                        <div style="width:60px; height:80px; display:flex; align-items:center; justify-content:center; background:#fafafa; border-radius:4px; border:1px solid #eee; cursor:pointer;"
                             onclick="openImageModal(<?= $idx ?>, <?= $i ?>)">
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

                <!-- Status Change Form -->
                <form method="post" style="margin-top:10px; display:flex; align-items:center; justify-content: space-between; gap:8px;">
                    <input type="hidden" name="report_id" value="<?= (int)$report['report_id'] ?>">
                    <div class="">
                        <label for="status_<?= (int)$report['report_id'] ?>" style="font-weight:500;"><b>Change Status:</b></label>
                        <select name="new_status" id="status_<?= (int)$report['report_id'] ?>" style="padding:4px 10px; border-radius:4px; border:1px solid #ccc;">
                            <option value="pending" <?= $report['status']=='pending'?'selected':'' ?>>Pending</option>
                            <option value="in_progress" <?= $report['status']=='in_progress'?'selected':'' ?>>In Progress</option>
                            <option value="resolved" <?= $report['status']=='resolved'?'selected':'' ?>>Resolved</option>
                        </select>
                    </div>
                    <button type="submit" class="primary-btn" style="padding:4px 16px; margin-top:0; border-radius:4px;">Update</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Floating Image Modal -->
    <div id="imageModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); align-items:center; justify-content:center;">
        <span onclick="closeImageModal()" style="position:absolute; top:30px; right:40px; font-size:36px; color:#fff; cursor:pointer; z-index:10001;">X</span>
        <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; width:100vw; height:100vh;">
            <button id="prevImgBtn" onclick="slideImage(-1)" style="position:absolute; left:40px; top:50%; transform:translateY(-50%); font-size:32px; background:rgba(0,0,0,0.3); color:#fff; border:none; border-radius:50%; width:48px; height:48px; cursor:pointer; z-index:10001;">&#8592;</button>
                <img id="modalImg" src="" alt="Report Image" style="max-width:80vw; max-height:80vh; border-radius:10px; box-shadow:0 4px 24px rgba(0,0,0,0.25); background:#fff;">
            <button id="nextImgBtn" onclick="slideImage(1)" style="position:absolute; right:40px; top:50%; transform:translateY(-50%); font-size:32px; background:rgba(0,0,0,0.3); color:#fff; border:none; border-radius:50%; width:48px; height:48px; cursor:pointer; z-index:10001;">&#8594;</button>
        </div>
    </div>
    
<?php endif; ?>

<script>
    // Track sorting state for each column
    const sortStates = Array(6).fill(null); // null = unsorted, 'asc' = ascending, 'desc' = descending
    
    function sortTable(columnIndex) {
        const table = document.querySelector(".inventory-table");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const isDateColumn = columnIndex === 5; // Date column
        const isPriceColumn = columnIndex === 4; // Price column
        
        // Toggle sort direction
        sortStates[columnIndex] = sortStates[columnIndex] === 'asc' ? 'desc' : 'asc';
        const sortDirection = sortStates[columnIndex];
        
        // Reset sort indicators on all headers
        table.querySelectorAll("th").forEach((th, index) => {
            if (index !== columnIndex) {
                th.innerHTML = th.textContent.trim();
                sortStates[index] = null;
            }
        });
        
        // Update sort indicator on current header
        const currentHeader = table.querySelectorAll("th")[columnIndex];
        currentHeader.innerHTML = `${currentHeader.textContent.trim()} `;
        
        // Sort rows
        rows.sort((rowA, rowB) => {
            const cellA = rowA.cells[columnIndex].textContent.trim();
            const cellB = rowB.cells[columnIndex].textContent.trim();
            
            let valueA, valueB;
            
            if (isDateColumn) {
                // Date comparison
                valueA = new Date(cellA);
                valueB = new Date(cellB);
            } else if (isPriceColumn) {
                // Price comparison (remove non-numeric characters)
                valueA = parseFloat(cellA.replace(/[^0-9.]/g, "")) || 0;
                valueB = parseFloat(cellB.replace(/[^0-9.]/g, "")) || 0;
            } else if (columnIndex === 0 || columnIndex === 2) {
                // ID columns (numeric comparison)
                valueA = parseInt(cellA) || 0;
                valueB = parseInt(cellB) || 0;
            } else {
                // Text comparison
                valueA = cellA.toLowerCase();
                valueB = cellB.toLowerCase();
            }
            
            if (valueA < valueB) return sortDirection === 'asc' ? -1 : 1;
            if (valueA > valueB) return sortDirection === 'asc' ? 1 : -1;
            return 0;
        });
        
        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }

    // Store all images for all reports in a JS array
    const allReportImages = <?= json_encode(array_map(function($r) {
        return array_map(function($img) {
            return ROOT . "/assets/images/uploads/report_images/" . esc($img);
        }, !empty($r['images']) ? $r['images'] : []);
    }, $reports)); ?>;
    console.log(allReportImages);
    let currentReportIdx = 0;
    let currentImgIdx = 0;

    function openImageModal(reportIdx, imgIdx) {
        currentReportIdx = reportIdx;
        currentImgIdx = imgIdx;
        showModalImage();
        document.getElementById('imageModal').style.display = 'flex';
    }

    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    function showModalImage() {
        const imgs = allReportImages[currentReportIdx];
        if (!imgs.length) return;
        document.getElementById('modalImg').src = imgs[currentImgIdx];
        document.getElementById('prevImgBtn').style.display = imgs.length > 1 ? 'block' : 'none';
        document.getElementById('nextImgBtn').style.display = imgs.length > 1 ? 'block' : 'none';
    }

    function slideImage(dir) {
        const imgs = allReportImages[currentReportIdx];
        if (!imgs.length) return;
        currentImgIdx = (currentImgIdx + dir + imgs.length) % imgs.length;
        showModalImage();
    }

    // Optional: Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") closeImageModal();
        if (e.key === "ArrowLeft") slideImage(-1);
        if (e.key === "ArrowRight") slideImage(1);
    });
</script>

<?php require_once 'agentFooter.view.php'; ?>