<?php require_once __DIR__ . '\..\agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/property/propertyHome'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Change Requests</h2>
</div>


<?php
if (empty($requests)) {
    echo '<div class="AA__property-management-container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #fff;">';
    echo '<p style="text-align: center;">No requests found</p>';
    echo '<img src="' . ROOT . '/assets/images/no.jpg" alt="No requests" style="width: 200px; height: auto; align-self: center; margin-top: 20px;">';
    echo '</div>';
} else {
?>
    <div class="AA__property-management-container">
        <!-- Search and Filter Section -->
        <div class="AA__filter-section">
            <div class="AA__search-box">
                <input type="text" placeholder="Search...">
                <button class="AA__search-btn"><i class="fa fa-search"></i></button>
            </div>
        </div>

        <!-- Properties Table -->
        <div class="AA__properties-table-container">
            <table class="AA__properties-table">
                <thead>
                    <tr>
                        <th>Property ID</th>
                        <th>Name</th>
                        <th>Owner Name</th>
                        <th class="AA__align_to_center">Actions</th>
                    </tr>
                </thead>
                <tbody id="AA__properties-table-body">
                    <?php
                    foreach ($requests as $request):
                    ?>
                        <tr class="AA__property-row" data-id="<?= $request->property_id ?>" data-name="<?= strtolower($request->name) ?>" data-owner="<?= strtolower($request->owner_name) ?>">
                            <td><?= $request->property_id ?></td>
                            <td><?= $request->name ?></td>
                            <td><?= $request->owner_name ?></td>
                            <td class="AA__action-buttons">
                                <button class="small-btn green" onclick="window.location.href='<?= ROOT ?>/dashboard/property/propertyView/<?= $request->property_id ?>'">View</button>
                                <button class="small-btn orange" onclick="window.location.href='<?= ROOT ?>/dashboard/property/comparePropertyUpdate/<?= $request->property_id ?>'">See Changes</button>
                            </td>
                        </tr>
                <?php endforeach;
                } ?>
                </tbody>
            </table>
        </div>

        <script>
            // Search functionality
            document.addEventListener('DOMContentLoaded', function() {
                // Get search input and filter select
                const searchInput = document.querySelector('.AA__search-box input');
                const statusFilter = document.querySelector('.AA__filter-options select');

                // Add event listeners for search and filter
                if (searchInput) {
                    searchInput.addEventListener('input', filterProperties);
                }

                if (statusFilter) {
                    statusFilter.addEventListener('change', filterProperties);
                }

                // Add click event listener to document for closing expanded panels
                document.addEventListener('click', function(event) {
                    // If click is not within an assignment panel or assign button
                    if (!event.target.closest('.AA__assignment-panel') &&
                        !event.target.closest('.small-btn.blue')) {
                        // Close all assignment panels
                        document.querySelectorAll('.AA__assignment-panel').forEach(panel => {
                            panel.classList.remove('AA__active');
                        });
                    }
                });

                // Function to filter properties based on search and status filter
                function filterProperties() {
                    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
                    const statusValue = statusFilter ? statusFilter.value.toLowerCase() : '';

                    const propertyRows = document.querySelectorAll('.AA__property-row');

                    propertyRows.forEach(row => {
                        const propertyName = row.getAttribute('data-name');
                        const ownerName = row.getAttribute('data-owner');
                        const status = row.getAttribute('data-status') || '';

                        // Get the panel associated with this row
                        const panelId = row.getAttribute('data-id');
                        const panel = document.getElementById('panel-' + panelId);

                        // Check if row matches search term and status filter
                        const matchesSearch =
                            propertyName.includes(searchTerm) ||
                            ownerName.includes(searchTerm);

                        const matchesStatus =
                            statusValue === '' ||
                            status === statusValue;

                        // Show/hide row based on filters
                        if (matchesSearch && matchesStatus) {
                            row.style.display = '';
                            if (panel) panel.style.display = ''; // Keep panel in DOM, but hidden unless active
                        } else {
                            row.style.display = 'none';
                            if (panel) panel.style.display = 'none'; // Hide panel completely
                        }
                    });
                }
            });
        </script>

<?php require_once __DIR__ . '\..\agentFooter.view.php'; ?>
