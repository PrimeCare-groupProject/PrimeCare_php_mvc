<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Agent Management </h2>
    <div class="flex-bar">
        <form class="search-container" method="GET">
            <input 
                type="text" 
                class="search-input" 
                name="searchterm" 
                value="<?= isset($_GET['searchterm']) ? esc($_GET['searchterm']) : "" ?>" 
                placeholder="Search Employee ..."
            >
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/managementhome/agentmanagement/addagent'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add New Agent</span>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/managementhome/agentmanagement/removeagents'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/delete.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Approve Removal</span>
        </div>
    </div>
</div>

<?php
    $fields = [
        'fname' => 'First Name',
        'lname' => 'Last Name',
        'email' => 'Email',
        'contact' => 'Contact',
    ];
    
    if (!empty($new) && !empty($old)) {
        foreach ($new as $index => $newAgent) {
            $oldAgent = $old[$index] ?? null;
            if (!$oldAgent) continue;
?>
            <div class="content_wrapper" style="height: auto;">
                <div class="employee-details-container">
                    <form method="POST" action="<?= ROOT ?>/dashboard/managementhome/agentmanagement/approval">
                        <div class="listing-table-for-customer-payments" style="display: flex; align-items: flex-start;">
                            <!-- Images -->
                            <div style="flex-shrink: 0; display: flex; padding: 10px 25px 5px 20px; flex-direction: column; gap: 0px;">
                                <div>
                                    <div style="font-weight: bold; text-align: center;">Before</div>
                                    <img src="<?= get_img($oldAgent->image_url) ?>" class="header-profile-picture" style="width: 160px; margin: 10px; height: 160px; object-fit: cover; border-radius: 50%;">
                                </div>
                                <div>
                                    <div style="font-weight: bold; text-align: center;">After</div>
                                    <img src="<?= get_img($newAgent->image_url) ?>" class="header-profile-picture" style="width: 160px; margin: 10px; height: 160px; object-fit: cover; border-radius: 50%;">
                                </div>
                            </div>

                            <!-- Comparison Fields -->
                            <div style="flex: 1; padding: 20px 25px 5px 20px; display: flex; flex-direction: column; gap: 0px;">
                                <?php foreach ($fields as $key => $label): 
                                    $oldValue = $oldAgent->$key ?? '';
                                    $newValue = $newAgent->$key ?? '';
                                    $isChanged = $oldValue !== $newValue;
                                ?>
                                    <div class="input-group">
                                        <div class="input-group-group">
                                            <label class="input-label"><?= $label ?></label>
                                            <input type="text" value="<?= htmlspecialchars($oldValue) ?>" class="input-field" disabled>
                                        </div>
                                        <div class="input-group-group">
                                            <label class="input-label">After change <?= $label ?></label>
                                            <input type="text" value="<?= htmlspecialchars($newValue) ?>" class="input-field" disabled 
                                                <?= $isChanged ? "style='border: 2px solid var(--red-color); font-weight: bold;'" : "" ?>>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <input type="hidden" name="changes[<?= $index ?>][pid]" value="<?= $newAgent->pid ?>">
                                <div class="input-group-aligned" style="margin-top: 15px;">
                                    <button type="submit" name="changes[<?= $index ?>][action]" value="approve" class="green btn">Approve</button>
                                    <button type="submit" name="changes[<?= $index ?>][action]" value="reject" class="red btn">Reject</button>
                                </div>
                                <p class="status-message" style="text-align: right; font-size: 15px; margin-bottom:-15px; color: var(--primary-color);"><?= $newAgent->change_timestamp?></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
<?php 
        }
    } else { 
?>
        <div class="employee-details-container"style="margin-top: 10px;">
            <p style="text-align: center; margin: 0px; color: #484848FF;">No changes to display.</p>
        </div>
<?php 
    } 
?>
    

<script>
    // Display loader on form submission
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            document.querySelector('.loader-container').style.display = '';
        });
    });

    // Display loader on link click
    document.querySelectorAll('a').forEach(link => {
        if (!link.getAttribute('href').startsWith('#')) {
            link.addEventListener('click', () => {
                document.querySelector('.loader-container').style.display = '';
            });
        }
    });
</script>

<?php require_once 'managerFooter.view.php'; ?>
