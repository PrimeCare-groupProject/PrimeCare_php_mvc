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

<div>
    <!-- <?php if (!empty($preinspection)): ?>
        <?php $propertyCount = 1; // Initialize property counter 
        ?>
        <?php foreach ($preinspection as $preinspect): ?>
            <div class="preInspection">
                <div class="preInspection-header">
                    <h3>Property <?= $propertyCount ?></h3>
                    <form method="POST" action="<?= ROOT ?>/Serve/preInspect" enctype="multipart/form-data">
                        <input type="hidden" name="property_id" value="<?= $preinspect->property_id ?>">
                        <button type="submit" class="primary-btn">Update</button>
                    </form>
                </div>
                <div class="input-group1">
                    <div class="input-group2">
                        <div class="input-group">
                            <div class="input-group-aligned">
                                <span class="input-label-aligend1"><strong>Property ID:</strong></span><input class="input-field2" value="<?= $preinspect->property_id ?>" readonly>
                            </div>
                            <div class="input-group-aligned">
                                <span class="input-label-aligend1"><strong>Owner ID:</strong></span><input class="input-field2" value="<?= $preinspect->person_id ?>" readonly>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-aligned">
                                <span class="input-label-aligend1"><strong>Property Name:</strong></span><input class="input-field2" value="<?= $preinspect->name ?>" readonly>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-aligned">
                                <span class="input-label-aligend1"><strong>Property Address:</strong></span><input class="input-field2" value="<?= $preinspect->address ?>" readonly>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-aligned">
                                <span class="input-label-aligend1"><strong>Description:</strong></span><input class="input-field2" value="<?= $preinspect->description ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div>
                        <img class="image-card" src="<?= ROOT ?>/assets/images/uploads/property_images/<?= explode(',', $preinspect->property_images)[0] ?>" alt="">
                    </div>
                </div>
            </div>
            <?php $propertyCount++; // Increment property count 
            ?>
        <?php endforeach; ?> 
    <?php else: ?>
        <p>No preInspection found.</p>
    <?php endif; ?> -->



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
                if (empty($preinspection)) {
                    echo "<tr><td colspan='4'>No pre-inspection properties found.</td></tr>";
                } else {
                    foreach ($preinspection as $property):
                ?>
                        <tr class="AA__property-row" data-id="<?= $property->property_id ?>" data-name="<?= strtolower($property->name) ?>" data-owner="<?= strtolower($property->owner_name) ?>">
                            <td><?= $property->property_id ?></td>
                            <td><?= $property->name ?></td>
                            <td><?= $property->owner_name ?></td>
                            <td class="AA__action-buttons">
                                <!-- <button class="small-btn green" onclick="window.location.href='<?= ROOT ?>/dashboard/preInspection/reportGen/<?= $property->property_id ?>'">View</button> -->
                                <a href="<?= ROOT ?>/dashboard/preInspection/showReport/<?= $property->property_id ?>">
                                    <button class="btn btn-sm btn-primary">Generate Report</button>
                                </a>

                            </td>
                        </tr>
                <?php endforeach;
                } 
                ?>
            </tbody>
        </table>

    </div>


</div>

<script>
    function searchProperty() {
        let searchInput = document.getElementById("searchInput").value;
        window.location.href = "?search=" + encodeURIComponent(searchInput);
    }
</script>


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

<?php require_once 'agentFooter.view.php'; ?>