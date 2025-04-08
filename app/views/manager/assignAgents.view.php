<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome/propertymanagement'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>assign agents</h2>
    <div class="flex-bar">
        <form class="search-container" method="GET">
            <input
                type="text"
                class="search-input"
                name="searchterm"
                placeholder="find property ...">
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
    </div>
</div>
<div>
    <?php foreach($properties as $property): ?>
        <div class="property-assign-container">
            <div class="property-assign-header">
                <h3 style="color: var(--black-color);"><?= $property->name ?></h3>
                <div class="AG__button_class">
                    <button class="primary-btn green">See property</button>
                    <button class="primary-btn">Assign</button>
                </div>
            </div>
            <div class="input-group">
                <div class="input-group">
                    <span class="input-label fixed_width_for_label"><strong>Property ID:</strong></span><span class="input-field small_margin"><?= $property->property_id ?></span>
                </div>
                <div class="input-group">
                    <span class="input-label fixed_width_for_label"><strong>Agent:</strong></span>

                    <select class="input-field small_margin" style="background-color: rgba(var(--primary-blue-rgb) , 0.2); border: transparent;">
                        <option value="notassign">Select Agent</option>
                        <option value="ag1"><img src="<?= ROOT ?>/assets/images/user.png" class="extra-small-images" /> Agent 1</option>
                        <option value="ag2"><img src="<?= ROOT ?>/assets/images/user.png" class="extra-small-images" /> Agent 2</option>
                        <option value="ag3"><img src="<?= ROOT ?>/assets/images/user.png" class="extra-small-images" /> Agent 3</option>
                        <option value="ag4"><img src="<?= ROOT ?>/assets/images/user.png" class="extra-small-images" /> Agent 4</option>
                    </select>

                </div>

            </div>
            <div class="input-group">
                <div class="input-group">
                    <span class="input-label fixed_width_for_label"><strong>Type:</strong></span><span class="input-field small_margin"><?= $property->type ?></span>
                </div>
                <div class="input-group">
                    <span class="input-label fixed_width_for_label"><strong>Date:</strong></span><span class="input-field small_margin"><?= date('Y-m-d', strtotime($property->created_at)) ?></span>
                </div>
            </div>
            <div class="input-group">
                <div class="input-group">
                    <span class="input-label fixed_width_for_label"><strong>Address:</strong></span><span class="input-field small_margin"><?= $property->address ?></span>
                </div>
            </div>
            <div class="input-group">
                <div class="input-group">
                    <span class="input-label fixed_width_for_label"><strong>Description:</strong></span><span class="input-field small_margin"><?= $property->description ?></span>
                </div>
            </div>
        </div>

    <?php endforeach; ?>
</div>

<?php require_once 'managerFooter.view.php'; ?>