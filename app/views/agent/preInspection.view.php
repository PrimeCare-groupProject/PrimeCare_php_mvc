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
                <button class="search-btn" >
                    <img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons">
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<div>
    <?php if (!empty($preinspection)): ?>
        <?php $propertyCount = 1; // Initialize property counter ?>
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
                        <img class="image-card" src="<?= ROOT ?>/assets/images/resort.png" alt="">
                    </div>
                </div>
            </div>
            <?php $propertyCount++; // Increment property count ?>
        <?php endforeach; ?> 
    <?php else: ?>
        <p>No preInspection found.</p>
    <?php endif; ?>
</div>

<script>
function searchProperty() {
    let searchInput = document.getElementById("searchInput").value;
    window.location.href = "?search=" + encodeURIComponent(searchInput);
}
</script>


<?php require_once 'agentFooter.view.php'; ?>
