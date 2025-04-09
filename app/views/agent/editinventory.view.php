<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/inventory'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Edit inventory</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Inventory/update" enctype="multipart/form-data">
    <div class="owner-addProp-container">
          <div class="owner-addProp-form-left">
               <img src="<?= ROOT ?>/assets/images/booking1.png" alt="Back" class="bookingimg">
               
                <!-- Hidden field for service_id -->
                <input type="hidden" name="inventory_id" value="<?=  $inventory->inventory_id ?>">

               <label class="input-label">Inventory Name</label>
               <input type="text" name="inventory_name" value="<?= $inventory->inventory_name ?>" class="input-field" required>

               <label class="input-label">Inventory_type</label>
               <input type="text" name="inventory_type" value="<?= $inventory->inventory_type ?>" class="input-field" required>

               <label class="input-label">Date</label>
               <input type="date" name="date" value="<?= $inventory->date ?>" class="input-field" required>

               <label class="input-label">Unit Price</label>
               <input type="text" name="unit_price" value="<?= $inventory->unit_price ?>" class="input-field" required>

               <label class="input-label">Quantity</label>
               <input type="text" name="quantity" value="<?= $inventory->quantity ?>" class="input-field" required>

          </div>

        <div class="owner-addProp-form-right">
               
               <label class="input-label">Seller Name</label>
               <input type="text" name="seller_name" value="<?= $inventory->seller_name ?>" class="input-field" required>

               <label class="input-label">Seller Address</label>
               <input type="text" name="seller_address" value="<?= $inventory->seller_address ?>" class="input-field" required>

               <label class="input-label">Property ID</label>
               <input type="text" name="property_id" value="<?= $inventory->property_id ?>" class="input-field" required>

               <label class="input-label">Property Name</label>
               <input type="text" name="property_name" value="<?= $inventory->property_name ?>" class="input-field" required>

               <label class="input-label">Description</label>
               <textarea name="description" class="input-field1" required><?= $inventory->description ?></textarea>

            <div class="buttons-to-right">
                <button type="submit" name="action" value="save" class="primary-btn">Save</button>
                <button type="submit" name="action" value="cancel" class="secondary-btn">Cancel</button>
            </div>

            <?php if (isset($_SESSION['flash_message'])): ?>
               <div class="flash-message">
                    <?= $_SESSION['flash_message']; ?>
                    <?php unset($_SESSION['flash_message']); ?> <!-- Clear the message after displaying -->
               </div>
          <?php endif; ?>
        </div>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let descriptionBox = document.querySelector(".description-box");
        
        if (descriptionBox) {
            descriptionBox.style.height = "auto"; // Reset height to auto
            descriptionBox.style.height = descriptionBox.scrollHeight + "px"; // Adjust height based on content
        }
    });
</script>

<?php require_once 'agentFooter.view.php'; ?>