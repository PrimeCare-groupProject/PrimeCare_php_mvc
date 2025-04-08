<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>inventory</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/inventory/newinventory'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add new inventory</span>
        </div>
    </div>
</div>
<div class="inventory-details-container">
    <table class="inventory-table">
        <thead>
            <tr>
                <th onclick="sortTable(0)">Inventory ID ⬆⬇</th>
                <th>Inventory Name</th>
                <th onclick="sortTable(2)">Property ID ⬆⬇</th>
                <th>Property Name</th>
                <th onclick="sortTable(4)">Price ⬆⬇</th>
                <th onclick="sortTable(5)">Date ⬆⬇</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($inventories)) : ?>
                <?php foreach ($inventories as $inventory) : ?>
                    <tr>
                        <td><?= $inventory->inventory_id ?></td>
                        <td><?= $inventory->inventory_name ?></td>
                        <td><?= $inventory->property_id ?></td>
                        <td><?= $inventory->property_name ?></td>
                        <td><?= $inventory->total_price ?></td>
                        <td><?= $inventory->date ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr style="height: 240px; background-color: #f8f9fa;">
                    <td colspan="6" style="text-align: center; vertical-align: middle; padding: 0;">
                        <div style="width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px 0;">
                            <!-- Empty state icon -->
                            <div style="margin-bottom: 15px; opacity: 0.5;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <path d="M13 2v7h7"></path>
                                    <circle cx="12" cy="15" r="4"></circle>
                                    <line x1="9" y1="15" x2="15" y2="15"></line>
                                </svg>
                            </div>
                            
                            <!-- Message -->
                            <h3 style="font-size: 16px; color: #555; margin: 0; font-weight: 500;">No inventory items found</h3>
                            <p style="font-size: 14px; color: #777; margin: 8px 0 0 0; max-width: 400px;">
                                There are currently no inventory items matching your criteria.
                            </p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'agentFooter.view.php'; ?>