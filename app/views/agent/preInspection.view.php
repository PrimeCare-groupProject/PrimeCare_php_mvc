<?php require_once 'agentHeader.view.php'; ?>

<?php if (!empty($preinspection)): ?>
    <?php
    // Check if a search query is set
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Filter the properties based on the search query
    $preinspection = array_filter($preinspection, function ($preinspect) use ($searchQuery) {
        return stripos($preinspect->name, $searchQuery) !== false; // Case-insensitive search
    });
    ?>
<?php endif; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>PreInspection</h2>
    <div class="flex-bar">
        <div class="search-container">
            <?php if (!empty($preinspection)): ?>
                <input type="text" id="searchInput" class="search-input" placeholder="Search Property Name..." value="<?= htmlspecialchars($searchQuery) ?>">
                <button class="search-btn" onclick="searchProperty()">
                    <img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons">
                </button>
            <?php else: ?>
                <button class="search-btn">
                    <img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons">
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
if (empty($preinspection)) {
    echo '<div class="AA__property-management-container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #fff;">';
    echo '<p style="text-align: center;">No Assignments found</p>';
    echo '<img src="' . ROOT . '/assets/images/no.jpg" alt="No requests" style="width: 200px; height: auto; align-self: center; margin-top: 20px;">';
    echo '</div>';
} else {
?>
    <div class="AA__property-management-container">
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
                    foreach ($preinspection as $request):
                    ?>
                        <tr class="AA__property-row" data-id="<?= $request->property_id ?>" data-name="<?= strtolower($request->name) ?>" data-owner="<?= strtolower($request->owner_name) ?>">
                            <td><?= $request->property_id ?></td>
                            <td><?= $request->name ?></td>
                            <td><?= $request->owner_name ?></td>
                            <td class="AA__action-buttons">
                                <button class="small-btn orange" onclick="window.location.href='<?= ROOT ?>/dashboard/preInspection/viewProperty/<?= $request->property_id ?>'">View</button>
                                <button class="small-btn green" onclick="window.location.href='<?= ROOT ?>/dashboard/preInspection/showReport/<?= $request->property_id ?>'">Generate Report</button>
                                <button class="small-btn blue" onclick="window.location.href='<?= ROOT ?>/dashboard/preInspection/submitReport/<?= $request->property_id ?>'">Submit Report</button>
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
                const topSearchInput = document.getElementById('searchInput');

                if (topSearchInput) {
                    topSearchInput.addEventListener('input', filterProperties);
                }

                function filterProperties() {
                    const searchTerm = topSearchInput.value.toLowerCase();
                    const propertyRows = document.querySelectorAll('.AA__property-row');

                    propertyRows.forEach(row => {
                        const propertyName = row.getAttribute('data-name');
                        const ownerName = row.getAttribute('data-owner');

                        const matchesSearch =
                            propertyName.includes(searchTerm) ||
                            ownerName.includes(searchTerm);

                        row.style.display = matchesSearch ? '' : 'none';
                    });

                    // Close all expanded panels when filtering
                    document.querySelectorAll('.AA__assignment-panel').forEach(panel => {
                        panel.classList.remove('AA__active');
                    });
                }

                // Optional: clicking anywhere outside panels closes them
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.AA__assignment-panel') &&
                        !event.target.closest('.small-btn.blue')) {
                        document.querySelectorAll('.AA__assignment-panel').forEach(panel => {
                            panel.classList.remove('AA__active');
                        });
                    }
                });
            });

            // Function to toggle assignment panel visibility
            function toggleAssignPanel(event, propertyId) {
                event.stopPropagation();
                const panel = document.getElementById('panel-' + propertyId);

                document.querySelectorAll('.AA__assignment-panel').forEach(item => {
                    if (item.id !== 'panel-' + propertyId) {
                        item.classList.remove('AA__active');
                    }
                });

                panel.classList.toggle('AA__active');

                panel.onclick = function(e) {
                    e.stopPropagation();
                };
            }

            // Optional: Trigger search on search button click
            function searchProperty() {
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    const event = new Event('input');
                    searchInput.dispatchEvent(event);
                }
            }
        </script>






        <?php require_once 'agentFooter.view.php'; ?>