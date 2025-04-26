<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome/propertymanagement'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Assign Agents</h2>
    <div class="flex-bar">
        <form class="search-container" method="GET">
            <input
                type="text"
                class="search-input"
                name="searchterm"
                id="searchInput"
                placeholder="Search contact message ...">
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
    </div>
</div>
<div class="AA__property-management-container">
    <div class="AA__properties-table-container">
        <?php if ($properties != null) { ?>
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
                    foreach ($properties as $property):
                    ?>
                        <tr class="AA__property-row" data-id="<?= $property->property_id ?>" data-name="<?= strtolower($property->name) ?>" data-owner="<?= strtolower($property->owner_name) ?>">
                            <td><?= $property->property_id ?></td>
                            <td><?= $property->name ?></td>
                            <td><?= $property->owner_name ?></td>
                            <td class="AA__action-buttons">
                                <button class="small-btn green" onclick="window.location.href='<?= ROOT ?>/dashboard/managementhome/propertymanagement/propertyView/<?= $property->property_id ?>'">View</button>
                                <button class="small-btn orange" onclick="toggleAssignPanel(event, '<?= $property->property_id ?>')">Assign</button>
                            </td>
                        </tr>
                        <tr class="AA__assignment-panel" id="panel-<?= $property->property_id ?>">
                            <td colspan="5">
                                <div class="AA__assignment-details">
                                    <div class="AA__agent-selection">
                                        <select class="AA__agent-dropdown" id="agent-select-<?= $property->property_id ?>">
                                            <?php
                                            if ($agents == null) {
                                                echo "<option value=''>No agents available</option>";
                                            } else {
                                                foreach ($agents as $agent): ?>
                                                    <option value="<?= $agent->pid ?>"><?= $agent->fname . ' ' . $agent->lname ?></option>
                                            <?php endforeach;
                                            } ?>
                                        </select>
                                    </div>
                                    <div>
                                        <button class="secondary-btn blue-solid" onclick="confirmAssignment(<?= $property->property_id ?>)">Confirm Assignment</button>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <script>
                            function confirmAssignment(propertyId) {
                                const agentSelect = document.getElementById(`agent-select-${propertyId}`);
                                const selectedAgentId = agentSelect.value;
                                const url = `<?= ROOT ?>/dashboard/managementhome/propertymanagement/confirmAssign/${propertyId}/${selectedAgentId}`;
                                window.location.href = url;
                            }
                        </script>

                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php } else {
            echo "<tr><td colspan='4' class='no-data'>No properties available</td></tr>";
        } ?>
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

                // Close all expanded panels when filtering
                document.querySelectorAll('.AA__assignment-panel').forEach(panel => {
                    panel.classList.remove('AA__active');
                });
            }
        });

        // Function to toggle assignment panel visibility
        function toggleAssignPanel(event, propertyId) {
            event.stopPropagation();
            const panel = document.getElementById('panel-' + propertyId);

            // Close all other open panels
            document.querySelectorAll('.AA__assignment-panel').forEach(item => {
                if (item.id !== 'panel-' + propertyId) {
                    item.classList.remove('AA__active');
                }
            });

            // Toggle the selected panel
            panel.classList.toggle('AA__active');

            // Add click handler to the panel to prevent closing when clicking inside
            panel.onclick = function(e) {
                e.stopPropagation();
            };
        }
    </script>

    <script>
        document.getElementById("searchInput").addEventListener("keyup", function() {
            const input = this.value.toLowerCase();
            const rows = document.querySelectorAll(".AA__property-row");

            rows.forEach(row => {
                const propertyName = row.getAttribute('data-name') || '';
                const ownerName = row.getAttribute('data-owner') || '';

                if (propertyName.includes(input) || ownerName.includes(input)) {
                    row.style.display = "";
                    const panel = document.getElementById('panel-' + row.getAttribute('data-id'));
                    if (panel) panel.style.display = "";
                } else {
                    row.style.display = "none";
                    const panel = document.getElementById('panel-' + row.getAttribute('data-id'));
                    if (panel) panel.style.display = "none";
                }
            });
        });
    </script>

    <?php require_once 'managerFooter.view.php'; ?>