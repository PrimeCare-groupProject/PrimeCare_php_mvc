<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/repairings'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>New Repair</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Serve/create" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">Repair Name</label>
            <input type="text" name="name" placeholder="Enter repair name" class="input-field" required>

            <label class="input-label">Cost Of Hour</label>
            <input type="text" name="cost_per_hour" placeholder="Enter repair name" class="input-field" required>
            
            <label class="input-label">Description About The Repair</label>
            <textarea name="description" placeholder="description" class="input-field" required></textarea>

            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Submit</button>
            </div>
        </div>
    </div>
</form>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="flash-message">
        <?= $_SESSION['flash_message']; ?>
        <?php unset($_SESSION['flash_message']); ?> <!-- Clear the message after displaying -->
    </div>
<?php endif; ?>

<?php require_once 'agentFooter.view.php'; ?>