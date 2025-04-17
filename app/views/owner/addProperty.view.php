<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/propertyListing'>
        <img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons">
    </a>
    <h2>ADD PROPERTIES</h2>
</div>

<?php
if (isset($_SESSION['flash'])) {
    flash_message();
}
?>

<form method="POST" action="<?= ROOT ?>/dashboard/propertylisting/create" enctype="multipart/form-data">
    <div class="owner-addProp-form">
        <h3 class="form-headers no-top-border">Property Details</h3>
        <label class="input-label">Name Of Property*</label>
        <input type="text" name="name" placeholder="Enter Property Name" class="input-field" required>

        <label class="input-label">Type*</label>
        <select name="type" class="input-field" id="property-type" required onchange="toggleRentField()">
            <option value="House">House</option>
            <option value="Apartment">Apartment</option>
            <option value="Villa">Villa</option>
            <option value="Studio">Studio</option>
            <option value="farmhouse">Farmhouse</option>
        </select>

        <label class="input-label">Description*</label>
        <textarea name="description" placeholder="Write About Property" class="input-field" required></textarea>


        <h3 class="form-headers">Basic Property Information</h3>
        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Address*</label>
                <input type="text" name="address" placeholder="Enter Address" class="input-field" required>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">City*</label>
                <input type="text" name="city" placeholder="Enter Property City" class="input-field" required>
            </div>
            <div class="input-group-group">
                <label class="input-label">Zip Code*</label>
                <input type="text" name="zipcode" placeholder="Enter Property Zip Code" class="input-field" required>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">State*</label>
                <input type="text" name="state_province" placeholder="Enter Property State" class="input-field" required>
            </div>
            <div class="input-group-group">
                <label class="input-label">Country*</label>
                <input type="text" name="country" placeholder="Enter Property Country" class="input-field" required>
            </div>
        </div>

        <h3 class="form-headers">Rental Information*</h3>

        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Purpose*</label>
                <select name="purpose" id="purpose" class="input-field" onchange="handlePurposeChange()">
                    <option value="">-- Select Purpose --</option>
                    <option value="Rent">For Rent</option>
                    <option value="Safeguard">For Safeguard (Security Purposes)</option>
                    <option value="Vacation_Rental">Vacation Rental</option>
                </select>
            </div>
        </div>

        <!-- Section shown only for Rent -->
        <div class="input-group" id="rent-fields" style="display:none;">
            <div class="input-group-group">
                <label class="input-label">Rental Period*</label>
                <select name="rental_period" class="input-field">
                    <option value="Monthly">Monthly</option>
                    <option value="Annually">Annually</option>
                    <option value="Daily">Daily</option>
                </select>
            </div>
            <div class="input-group-group">
                <label class="input-label">Duration (in months)*</label>
                <input type="number" id="duration" name="duration" class="input-field" min="1" value="1" oninput="calculateRental()">
            </div>
            <div class="input-group-group">
                <label class="input-label">Rental Price*</label>
                <input type="number" name="rental_price" placeholder="Enter the rental price" class="input-field">
            </div>
        </div>

        <!-- Section shown for Safeguard or Vacation Rental -->
        <!-- <div class="input-group" id="other-fields" style="display:none;">
            <div class="input-group-group">
                <label class="input-label">Duration (in days)*</label>
                <input type="number" id="safeguard_duration" name="duration" class="input-field" min="7" value="7" oninput="calculateRental()">
            </div>
            <div class="input-group-group">
                <label class="input-label">Base Rate*</label>
                <input type="number" id="safeguard_base_rate" name="safeguard_base_rate" class="input-field blue-input-field" value="<?= RENTAL_PRICE ?>" readonly>
            </div>
            <div class="input-group-group">
                <label class="input-label">Estimated Total</label>
                <input type="text" id="total_display" class="input-field blue-input-field-solid" readonly>
            </div>
        </div> -->

        <!-- Section shown for Safeguard or Vacation Rental -->
        <div class="input-group" id="other-fields" style="display:none;">
            <div class="input-group-group">
                <label class="input-label">Start Date*</label>
                <input type="date" id="start_date" name="start_date" class="input-field" onchange="updateEndDateLimit(); calculateRentalFromDates();" min="<?= date('Y-m-d') ?>">
            </div>
            <div class="input-group-group">
                <label class="input-label">End Date*</label>
                <input type="date" id="end_date" name="end_date" class="input-field" onchange="calculateRentalFromDates();">
            </div>
            <div class="input-group-group">
                <label class="input-label">Base Rate*</label>
                <input type="number" id="safeguard_base_rate" name="safeguard_base_rate" class="input-field blue-input-field" value="<?= RENTAL_PRICE ?>" readonly>
            </div>
            <div class="input-group-group">
                <label class="input-label">Estimated Total</label>
                <input type="text" id="total_display" class="input-field blue-input-field-solid" readonly>
            </div>
        </div>



        <div id="rest-of-fields" style="display:none;">

            <h3 class="form-headers">Property Specifications</h3>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Year Built*</label>
                    <input type="text" name="year_built" placeholder="Enter Property Year Built" class="input-field">
                </div>
                <div class="input-group-group">
                    <label class="input-label">Size Of Property In Square Roots*</label>
                    <input type="number" name="size_sqr_ft" placeholder="Enter Property Size" class="input-field">
                </div>
                <div class="input-group-group">
                    <label class="input-label">Number of Floors*</label>
                    <input type="number" name="number_of_floors" placeholder="Enter Number of floors" class="input-field">
                </div>
            </div>
            <div class="input-group-group">
                <label class="input-label">Floor Plan</label>
                <textarea name="floor_plan" placeholder="Enter a Description About The Property" class="input-field"></textarea>
            </div>


            <h3 class="form-headers">Room Details</h3>
            <div class="input-group-group">
                <label class="input-label">No Of Units Of Property*</label>
                <input type="number" name="units" placeholder="Enter No Of Property Units" class="input-field">
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Bedrooms*</label>
                    <input type="number" name="bedrooms" placeholder="Enter No Of Bedrooms" class="input-field">
                </div>
                <div class="input-group-group">
                    <label class="input-label">Bathrooms*</label>
                    <input type="number" name="bathrooms" placeholder="Enter No of Bathrooms" class="input-field">
                </div>
            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Kitchen*</label>
                    <input type="number" name="kitchen" placeholder="Enter No Of Kitchens" class="input-field">
                </div>
                <div class="input-group-group">
                    <label class="input-label">Living Rooms*</label>
                    <input type="number" name="living_room" placeholder="Enter No of Living Rooms" class="input-field">
                </div>
            </div>


            <h3 class="form-headers">Furnishing Details</h3>
            <div class="input-group">
                <label class="input-label">Furnished*</label>
                <select name="furnished" class="input-field">
                    <option value="Fully Furnished">Fully Furnished</option>
                    <option value="Semi-Furnished">Semi-Furnished</option>
                    <option value="Unfurnished">Unfurnished</option>
                </select>
            </div>
            <div class="input-group-group">
                <label class="input-label">Furnished Description*</label>
                <textarea name="furniture_description" placeholder="Enter a Furnished Description" class="input-field"></textarea>
            </div>



            <h3 class="form-headers">Parking Details</h3>
            <div class="input-group">
                <label class="input-label">Parking*</label>
                <select name="parking" class="input-field">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Parking Slots*</label>
                    <input type="number" name="parking_slots" placeholder="Enter No Of Parking Slots" class="input-field">
                </div>
                <div class="input-group-group">
                    <label class="input-label">Type Of Parking*</label>
                    <select name="type_of_parking" class="input-field">
                        <option value="Covered Garage">Covered Garage</option>
                        <option value="Open Parking">Open Parking</option>
                        <option value="Street Parking">Street Parking</option>
                        <option value="Carport">Carport</option>
                        <option value="Underground Parking">Underground Parking</option>
                    </select>
                </div>
            </div>


            <h3 class="form-headers">Amenities</h3>
            <div class="input-group-group">
                <label class="input-label">Utilities Included*</label>
                <div class="checkbox-group checkbox-group-column extra-margin-botton">
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label"><input type="checkbox" name="utilities_included[]" value="Electricity"> Electricity</label>
                        <label class="inline-label"><input type="checkbox" name="utilities_included[]" value="Water Supply"> Water Supply</label>
                        <label class="inline-label"><input type="checkbox" name="utilities_included[]" value="Internet"> Internet</label>
                        <label class="inline-label"><input type="checkbox" name="utilities_included[]" value="Gas Connection"> Gas Connection</label>
                    </div>
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label"><input type="checkbox" name="utilities_included[]" value="Cable TV"> Cable TV</label>
                        <label class="inline-label"><input type="checkbox" name="utilities_included[]" value="Solar Power"> Solar Power</label>
                        <label class="inline-label"><input type="checkbox" name="utilities_included[]" value="Backup Generator"> Backup Generator</label>
                        <label class="inline-label"><input type="checkbox" name="utilities_included[]" value="Waste Disposal"> Waste Disposal</label>
                    </div>
                </div>
            </div>
            <div class="input-group-group">
                <label class="input-label">Additional Utilites*</label>
                <textarea name="additional_utilities" placeholder="Enter a Description about additional Utilities" class="input-field"></textarea>
            </div>


            <h3 class="form-headers">Security Details</h3>
            <div class="input-group-group">
                <div class="checkbox-group checkbox-group-column extra-margin-botton">
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label"><input type="checkbox" name="security_features[]" value="CCTV"> CCTV</label>
                        <label class="inline-label"><input type="checkbox" name="security_features[]" value="Security Guards"> Security Guards</label>
                        <label class="inline-label"><input type="checkbox" name="security_features[]" value="Intercom System"> Intercom System</label>
                    </div>
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label"><input type="checkbox" name="security_features[]" value="Access Control"> Access Control</label>
                        <label class="inline-label"><input type="checkbox" name="security_features[]" value="Fire Alarm"> Fire Alarm</label>
                        <label class="inline-label"><input type="checkbox" name="security_features[]" value="Gated Community"> Gated Community</label>
                    </div>
                </div>
            </div>



            <h3 class="form-headers">Additional Amenities*</h3>
            <div class="input-group-group">
                <div class="checkbox-group checkbox-group-column extra-margin-botton">
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label"><input type="checkbox" name="additional_amenities[]" value="Swimming Pool"> Swimming Pool</label>
                        <label class="inline-label"><input type="checkbox" name="additional_amenities[]" value="Gym"> Gym</label>
                        <label class="inline-label"><input type="checkbox" name="additional_amenities[]" value="Garden"> Garden</label>
                        <label class="inline-label"><input type="checkbox" name="additional_amenities[]" value="Elevator"> Elevator</label>
                    </div>
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label"><input type="checkbox" name="additional_amenities[]" value="Play Area"> Play Area</label>
                        <label class="inline-label"><input type="checkbox" name="additional_amenities[]" value="Clubhouse"> Clubhouse</label>
                        <label class="inline-label"><input type="checkbox" name="additional_amenities[]" value="Jogging Track"> Jogging Track</label>
                        <label class="inline-label"><input type="checkbox" name="additional_amenities[]" value="BBQ Area"> BBQ Area</label>
                    </div>
                </div>
            </div>

            <h3 class="form-headers">Special Instructions*</h3>
            <div class="input-group">
                <div class="checkbox-group checkbox-group-column">
                    <label class="inline-label"><input type="checkbox" name="special_instructions[]" value="No_Pets_Allowed"> No Pets Allowed</label>
                    <label class="inline-label"><input type="checkbox" name="special_instructions[]" value="No_Smoking_Inside_the_Property"> No Smoking Inside the Property</label>
                    <label class="inline-label"><input type="checkbox" name="special_instructions[]" value="Suitable_for_Families_Only"> Suitable for Families Only</label>
                    <label class="inline-label"><input type="checkbox" name="special_instructions[]" value="No_Loud_Music_or_Parties"> No Loud Music or Parties</label>
                    <label class="inline-label"><input type="checkbox" name="special_instructions[]" value="Maintenance_Fee_Included"> Maintenance Fee Included</label>
                </div>
                <div class="checkbox-group checkbox-group-column">
                    <label class="inline-label"><input type="checkbox" name="special_instructions[]" value="Tenant_Responsible_for_Utilities"> Tenant Responsible for Utilities</label>
                    <label class="inline-label"><input type="checkbox" name="special_instructions[]" value="Lease_Renewal_Option_Available"> Lease Renewal Option Available</label>
                    <label class="inline-label"><input type="checkbox" name="special_instructions[]" value="Immediate_Move_In_Available"> Immediate Move-In Available</label>
                    <label class="inline-label"><input type="checkbox" name="special_instructions[]" value="Background_Check_Required_for_Tenants"> Background Check Required for Tenants</label>
                </div>
            </div>
        </div>

        <h3 class="form-headers">Owner Information*</h3>
        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Owner Name*</label>
                <input type="text" name="owner_name" placeholder="Enter the Owner Name" class="input-field" value="<?= $_SESSION['user']->fname . ' ' . $_SESSION['user']->lname; ?>" required>
            </div>
            <div class="input-group-group">
                <label class="input-label">Owner Email*</label>
                <input type="text" name="owner_email" placeholder="Enter the Owner Email" class="input-field" value="<?= $_SESSION['user']->email; ?>" required>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Owner Contact Number*</label>
                <input type="text" name="owner_phone" placeholder="Enter the Owner Contact Number" class="input-field" value="<?= $_SESSION['user']->contact ?>" required>
            </div>
            <div class="input-group-group">
                <label class="input-label">Owner Additional Contact*</label>
                <input type="text" name="additional_contact" placeholder="Enter a Additional Contact" class="input-field" required>
            </div>
        </div>



        <h3 class="form-headers">Legal Details*</h3>
        <div class="input-group">
            <div class="checkbox-group checkbox-group-column">
                <label class="inline-label"><input type="checkbox" name="legal_details[]" value="Property_Ownership_Verified"> Property Ownership Verified</label>
                <label class="inline-label"><input type="checkbox" name="legal_details[]" value="Property_Free_from_Legal_Disputes"> Property Free from Legal Disputes</label>
                <label class="inline-label"><input type="checkbox" name="legal_details[]" value="All_Taxes_Paid_Up_to_Date"> All Taxes Paid Up to Date</label>
                <label class="inline-label"><input type="checkbox" name="legal_details[]" value="Rental_Agreement_Draft_Available"> Rental Agreement Draft Available</label>
                <label class="inline-label"><input type="checkbox" name="legal_details[]" value="Property_Insured"> Property Insured</label>
            </div>
            <div class="checkbox-group checkbox-group-column">
                <label class="inline-label"><input type="checkbox" name="legal_details[]" value="Compliance_with_Local_Housing_Laws"> Compliance with Local Housing Laws</label>
                <label class="inline-label"><input type="checkbox" name="legal_details[]" value="Tenant_Screening_Required"> Tenant Screening Required</label>
                <label class="inline-label"><input type="checkbox" name="legal_details[]" value="Lease_Agreement_Must_Be_Signed"> Lease Agreement Must Be Signed</label>
                <label class="inline-label"><input type="checkbox" name="legal_details[]" value="Security_Deposit_Refundable"> Security Deposit Refundable (Subject to Conditions)</label>
            </div>
        </div>





        <h3 class="form-headers">Photos & Documents*</h3>

        <label class="input-label">Upload Property Images (Max 6)*</label>
        <div class="owner-addProp-file-upload">
            <input type="file" name="property_images[]" id="property_images" class="input-field" multiple
                accept=".png, .jpg, .jpeg" data-max-files="6" onchange="handleFileSelect(event)" required>
            <div class="owner-addProp-upload-area">
                <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                <p class="upload-area-no-margin">Drop your files here</p>
                <button type="button" class="primary-btn" onclick="document.getElementById('property_images').click()">Choose File</button>
            </div>
        </div>

        <div id="uploaded-files" class="owner-addProp-uploaded-files">
            <!-- Uploaded files will be displayed here -->
        </div>


        <div id="preview-container" class="owner-addProp-uploaded-files">
            <!-- Preview area for selected images -->
        </div>

        <label class="input-label">Upload Property Ownership Details*</label>
        <div class="owner-addProp-file-upload">
            <input type="file" name="property_documents[]" id="ownership_details" accept=".pdf" onchange="handleFileSelectForDocs(event)" required>
            <div class="owner-addProp-upload-area">
                <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                <p class="upload-area-no-margin">Drop your files here</p>
                <button type="button" class="primary-btn" onclick="document.getElementById('ownership_details').click()">Choose File</button>
            </div>
        </div>
        <div id="preview-container-docs" class="owner-addProp-uploaded-files">
            <!-- Preview area for selected images -->
        </div>

        <div class="items-inline">
            <input type="checkbox" name="terms" required />
            <p>By Clicking, I Agree To Terms & Conditions.</p>
        </div>
        <div class="buttons-to-right">
            <button type="submit" class="primary-btn">Save</button>
        </div>

        <!-- <div class="errors" style="display: <?= !empty($property->errors) ? 'block' : 'none'; ?>">
            <p><?=
                $property->errors['name'] ??
                    $property->errors['description'] ??

                    $property->errors['address'] ??
                    $property->errors['city'] ??
                    $property->errors['zipcode'] ??
                    $property->errors['state_province'] ??
                    $property->errors['country'] ??

                    $property->errors['rental_price'] ??
                    $property->errors['rental_period'] ??


                    $property->errors['year_built'] ??
                    $property->errors['size_sqr_ft'] ??
                    $property->errors['number_of_floors'] ??
                    $property->errors['floor_plan'] ??

                    $property->errors['furniture_description'] ??
                    $property->errors['parking_slots'] ??
                    $property->errors['parking'] ??


                    $property->errors['owner_name'] ??
                    $property->errors['owner_email'] ??
                    $property->errors['owner_phone']
                ?>
            </p>
        </div> -->
    </div>
    <!-- </div> -->
</form>

<script>
    var ROOT = '<?= ROOT ?>';

    function toggleRentField() {
        const typeSelect = document.getElementById('property-type');
        const rentBasisField = document.getElementById('rent-basis-field');

        if (typeSelect.value === 'monthly') {
            rentBasisField.style.display = 'block';
        } else {
            rentBasisField.style.display = 'none';
        }
    }

    // Initialize display based on current selection
    document.addEventListener('DOMContentLoaded', toggleRentField);
</script>

<script>
    function handlePurposeChange() {
        const purpose = document.getElementById('purpose').value;
        const rentFields = document.getElementById('rent-fields');
        const otherFields = document.getElementById('other-fields');
        const restOfFeilds = document.getElementById('rest-of-fields');

        if (purpose === 'Rent') {
            rentFields.style.display = 'flex';
            otherFields.style.display = 'none';
            restOfFeilds.style.display = 'block';
        } else if (purpose === 'Safeguard' || purpose === 'Vacation_Rental') {
            rentFields.style.display = 'none';
            otherFields.style.display = 'flex';
            restOfFeilds.style.display = 'none';
        } else {
            rentFields.style.display = 'none';
            otherFields.style.display = 'none';
        }
    }

    // function calculateRental() {
    //     const duration = parseFloat(document.getElementById('duration').value) || 0;
    //     const rate = document.getElementById('base_rate').value;
    //     const total = duration * rate;
    //     document.getElementById('total_display').value = total > 0 ? `LKR ${total.toLocaleString()}` : '';
    // }
</script>

<script>
    function calculateRental() {
        const durationInput = document.getElementById('safeguard_duration');
        const baseRateInput = document.getElementById('safeguard_base_rate');
        const totalDisplay = document.getElementById('total_display');

        if (durationInput && baseRateInput && totalDisplay) {
            const duration = parseInt(durationInput.value) || 0;
            const baseRate = parseFloat(baseRateInput.value) || 0;

            const total = duration * baseRate;

            totalDisplay.value = total.toLocaleString('en-US', {
                style: 'currency',
                currency: 'LKR'
            });
        }
    }

    // Trigger calculation on page load
    document.addEventListener('DOMContentLoaded', calculateRental);
</script>


<script>
    function updateEndDateLimit() {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');

        if (startInput.value) {
            // Set end date's min to start date + 1 day
            const startDate = new Date(startInput.value);
            const minEndDate = new Date(startDate);
            minEndDate.setDate(startDate.getDate() + 1);

            // Format date to yyyy-mm-dd
            const yyyy = minEndDate.getFullYear();
            const mm = String(minEndDate.getMonth() + 1).padStart(2, '0');
            const dd = String(minEndDate.getDate()).padStart(2, '0');
            endInput.min = `${yyyy}-${mm}-${dd}`;

            // Clear end date if it's invalid
            if (new Date(endInput.value) < minEndDate) {
                endInput.value = '';
            }
        }
    }

    function calculateRentalFromDates() {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const baseRate = parseFloat(document.getElementById('safeguard_base_rate').value) || 0;
        const totalDisplay = document.getElementById('total_display');

        if (startInput.value && endInput.value) {
            const startDate = new Date(startInput.value);
            const endDate = new Date(endInput.value);

            if (endDate > startDate) {
                const diffTime = endDate - startDate;
                const duration = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                const total = duration * baseRate;

                totalDisplay.value = total.toLocaleString('en-US', {
                    style: 'currency',
                    currency: 'LKR'
                });
            } else {
                totalDisplay.value = "End date must be after start date";
            }
        } else {
            totalDisplay.value = "";
        }
    }
</script>




<script src="<?= ROOT ?>/assets/js/property/addproperty.js"></script>
<?php require_once 'ownerFooter.view.php'; ?>