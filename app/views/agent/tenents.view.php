<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>tenents</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/manageProviders/managetenents/addtenents'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add new tenent</span>
        </div>
    </div>
</div>
<div class="inventory-details-container">
    <table class="inventory-table">
        <thead>
            <tr>
                <th onclick="sortTable(0)">Tenent ID ⬆⬇</th>
                <th>Tenent Name</th>
                <th onclick="sortTable(2)">Property ID ⬆⬇</th>
                <th>Property Name</th>
                <th onclick="sortTable(4)">Total Payment ⬆⬇</th>
                <th onclick="sortTable(5)">Duration (Months) ⬆⬇</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bookings)) : ?>
                <?php foreach ($bookings as $booking) : ?>
                    <?php foreach ($persons as $person) : ?>
                         <?php foreach ($properties as $property) : ?>
                         <?php if ($booking-> customer_id == $person -> pid) : ?>
                              <?php if ($booking-> property_id == $property -> property_id) : ?>
                                <?php if ($booking-> accept_status == 'accepted') : ?>
                    <tr onclick="window.location.href='<?= ROOT ?>/dashboard/manageProviders/managetenents/edittenents/<?= $booking->booking_id ?>'">
                        <td><?= $booking->booking_id ?></td>
                        <td><?= $person->fname ?> <?= $person->lname ?></td>
                        <td><?= $booking->property_id ?></td>
                        <td><?= $property->name ?></td>
                        <td><?= $booking->price ?></td>
                        <td><?= $booking->renting_period ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php endforeach; ?>
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
                            <h3 style="font-size: 16px; color: #555; margin: 0; font-weight: 500;">No tenents found</h3>
                            <p style="font-size: 14px; color: #777; margin: 8px 0 0 0; max-width: 400px;">
                                There are currently no tenents matching your criteria.
                            </p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Track sorting state for each column
    const sortStates = Array(6).fill(null); // null = unsorted, 'asc' = ascending, 'desc' = descending
    
    function sortTable(columnIndex) {
        const table = document.querySelector(".inventory-table");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const isDateColumn = columnIndex === 5; // Date column
        const isPriceColumn = columnIndex === 4; // Price column
        
        // Toggle sort direction
        sortStates[columnIndex] = sortStates[columnIndex] === 'asc' ? 'desc' : 'asc';
        const sortDirection = sortStates[columnIndex];
        
        // Reset sort indicators on all headers
        table.querySelectorAll("th").forEach((th, index) => {
            if (index !== columnIndex) {
                th.innerHTML = th.textContent.trim();
                sortStates[index] = null;
            }
        });
        
        // Update sort indicator on current header
        const currentHeader = table.querySelectorAll("th")[columnIndex];
        currentHeader.innerHTML = `${currentHeader.textContent.trim()} `;
        
        // Sort rows
        rows.sort((rowA, rowB) => {
            const cellA = rowA.cells[columnIndex].textContent.trim();
            const cellB = rowB.cells[columnIndex].textContent.trim();
            
            let valueA, valueB;
            
            if (isDateColumn) {
                // Date comparison
                valueA = new Date(cellA);
                valueB = new Date(cellB);
            } else if (isPriceColumn) {
                // Price comparison (remove non-numeric characters)
                valueA = parseFloat(cellA.replace(/[^0-9.]/g, "")) || 0;
                valueB = parseFloat(cellB.replace(/[^0-9.]/g, "")) || 0;
            } else if (columnIndex === 0 || columnIndex === 2) {
                // ID columns (numeric comparison)
                valueA = parseInt(cellA) || 0;
                valueB = parseInt(cellB) || 0;
            } else {
                // Text comparison
                valueA = cellA.toLowerCase();
                valueB = cellB.toLowerCase();
            }
            
            if (valueA < valueB) return sortDirection === 'asc' ? -1 : 1;
            if (valueA > valueB) return sortDirection === 'asc' ? 1 : -1;
            return 0;
        });
        
        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }
</script>

<?php require_once 'agentFooter.view.php'; ?>

<!--<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/manageProviders/managetenents'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Tenents Details</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Booking/update" enctype="multipart/form-data">
<?php foreach ($images as $image): ?>
    <?php if ($booking->property_id == $image->property_id): ?>
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">

            <img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $image->image_url ?>" alt="Back" class="bookingimage">

            <input type="hidden" name="booking_id" value="<?=  $booking->booking_id ?>">

            <label class="input-label">Property Name</label>
            <input type="text" name="propertyname" value="<?=  $booking->name ?>" class="input-field" readonly>

            <label class="input-label">Province</label>
            <input type="text" name="province" value="<?= $booking->state_province?>" class="input-field" readonly>

            <label class="input-label">Country</label>
            <input type="text" name="country" value="<?= $booking->country?>" class="input-field" readonly>

            <label class="input-label">City</label>
            <input type="text" name="city" value="<?= $booking->city?>" class="input-field" readonly>

            <label class="input-label">Address</label>
            <input type="text" name="address" value="<?= $booking->address?>" class="input-field" readonly>

            <label class="input-label">Customer Name</label>
            <input type="text" name="customername" value="<?= $booking->fname?> <?= $booking->lname?>" class="input-field" readonly>

        </div>

        <div class="owner-addProp-form-right">

            <label class="input-label">NIC</label>
            <input type="text" name="customerId" value="<?= $booking->nic?>" class="input-field" readonly>

            <label class="input-label">Tenent ID</label>
            <input type="text" name="customerId" value="<?= $booking->pid?>" class="input-field" readonly>

            <label class="input-label">Email</label>
            <input type="email" name="email" value="<?= $booking->email?>" class="input-field" readonly>

            <label class="input-label">Mobile Number</label>
            <input type="email" name="mobilenumber" value="<?= $booking->contact?>" class="input-field" readonly>

            <label class="input-label">Booked Date</label>
            <input type="email" name="bookedDate" value="<?= $booking->booked_date?>" class="input-field" readonly>

            <label class="input-label">Lease Starting Date</label>
            <input type="email" name="startingDate" value="<?= $booking->start_date?>" class="input-field" readonly>

            <label class="input-label">Renting Period (Months)</label>
            <input type="email" name="renting_period" value="<?= $booking->renting_period?>" class="input-field" readonly>

            <label class="input-label">Rental (LKR)</label>
            <input type="email" name="rental" value="<?= $booking->price?>" class="input-field" readonly>


            <!--<label class="input-label">Upload Profile Image</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_image').click()">Choose File</button>
                </div>
            </div>

            <label class="input-label">Resume/CV</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_image').click()">Choose File</button>
                </div>
            </div>
            -->            
        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
</form>

<?php require_once 'agentFooter.view.php'; ?>-->