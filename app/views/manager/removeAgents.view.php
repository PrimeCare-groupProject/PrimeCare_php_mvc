<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome/agentmanagement'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Agent Removal </h2>
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
    </div>
</div>

<?php
    if (!empty($users)) {
        foreach ($users as $user) {
?>
            <div class="content_wrapper" style="height: auto;">
                <div class="employee-details-container AddnewAgentform">
                    <div class="listing-table-for-customer-payments" style="display: flex; align-items: flex-start;">
                        <!-- User Image -->
                        <div style="flex-shrink: 0; display: flex; justify-content: center; padding: 10px 25px 5px 20px; flex-direction: column; gap: 10px; align-items: center; justify-content: center; ">
                            <p style="color: #FF0000; font-weight: bold; text-align: center;"></p>
                            <div style="display: flex; justify-content: center;">
                                <img src="<?= get_img($user->image_url) ?>" class="header-profile-picture" style="width: 160px; margin: 10px; height: 160px; object-fit: cover; border-radius: 50%;">
                            </div>
                            <p class="profile-role ">PID - <?= $user->pid ?></p>
                            
                        </div>

                        <!-- User Details -->
                        <div style="flex: 1; padding: 20px 25px 5px 20px; display: flex; flex-direction: column;">
                            <div class="input-group">
                                <div class="input-group-group">
                                    <label for="fname" class="input-label">First Name</label>
                                    <input type="text" id="fname" value="<?= esc($user->fname) ?>" class="input-field" disabled>
                                </div>
                                <div class="input-group-group">
                                    <label for="lname" class="input-label">Last Name</label>
                                    <input type="text" id="lname" value="<?= esc($user->lname) ?>" class="input-field" disabled>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-group">
                                    <label for="nic" class="input-label">NIC</label>
                                    <input type="text" id="nic" value="<?= htmlspecialchars($user->nic) ?>" class="input-field" disabled>
                                </div>
                                <div class="input-group-group">
                                    <label for="email" class="input-label">Email</label>
                                    <input type="text" id="email" value="<?= htmlspecialchars($user->email) ?>" class="input-field" disabled>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-group">
                                    <label for="contact" class="input-label">Contact</label>
                                    <input type="text" id="contact" value="<?= htmlspecialchars($user->contact) ?>" class="input-field" disabled>
                                </div>
                                <div class="input-group-group">
                                    <label for="created_date" class="input-label">Created Date</label>
                                    <input type="text" id="created_date" value="<?= htmlspecialchars($user->created_date) ?>" class="input-field" disabled>
                                </div>
                            </div>
                            <form method="POST" action="<?= ROOT ?>/dashboard/managementhome/agentmanagement/removeagents">
                                <input type="hidden" name="pid" value="<?= $user->pid ?>">
                                <div class="input-group-aligned" style="margin: 15px 0 15px 0;">
                                    <button type="submit" name="action" value="reject" class="red btn">Reject</button>
                                    <button type="submit" name="action" value="approve" class="green btn">Approve</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
<?php 
        }
    } else { 
?>
        <div class="employee-details-container" style="margin-top: 10px;">
            <p style="text-align: center; margin: 0px; color: #484848FF;">No users to display.</p>
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
