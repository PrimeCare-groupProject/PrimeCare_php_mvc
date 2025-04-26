<?php require_once 'ownerHeader.view.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Call the function to set the initial state based on the current "purpose" value
        handlePurposeChange();

        // Trigger any other necessary calculations for Rent or other purposes
        calculateRental();
    });
</script>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/propertylisting/propertyunitowner/<?= $property->property_id ?>'>
        <img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons">
    </a>
    <h2>Update details on <span style="color: var(--green-color);"><?= $property->name ?><span></h2>
</div>

<form method="POST" action="<?= ROOT ?>/dashboard/propertylisting/update/<?= $property->property_id ?>" enctype="multipart/form-data">
    <div class="owner-addProp-form">
        <h3 class="form-headers no-top-border">Property Details</h3>
        <label class="input-label">Name Of Property*</label>
        <input type="text" name="name" placeholder="Enter Property Name" value="<?= $property->name ?>" class="input-field" required>

        <label class="input-label">Type*</label>
        <select name="type" class="input-field" id="property-type" required onchange="toggleRentField()">
            <option value="House" <?= $property->type == 'House' ? 'selected' : '' ?>>House</option>
            <option value="Apartment" <?= $property->type == 'Apartment' ? 'selected' : '' ?>>Apartment</option>
            <option value="Villa" <?= $property->type == 'Villa' ? 'selected' : '' ?>>Villa</option>
            <option value="Studio" <?= $property->type == 'Studio' ? 'selected' : '' ?>>Studio</option>
            <option value="Farmhouse" <?= $property->type == 'Farmhouse' ? 'selected' : '' ?>>Farmhouse</option>
        </select>


        <label class="input-label">Description*</label>
        <textarea name="description" placeholder="Write About Property" class="input-field" required><?= $property->description ?></textarea>


        <h3 class="form-headers">Basic Property Information</h3>
        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Address*</label>
                <input type="text" name="address" placeholder="Enter Address" value="<?= $property->address ?>" class="input-field" required>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">City*</label>
                <input type="text" name="city" placeholder="Enter Property City" value="<?= $property->city ?>" class="input-field" required>
            </div>
            <div class="input-group-group">
                <label class="input-label">Zip Code*</label>
                <input type="text" name="zipcode" placeholder="Enter Property Zip Code" value="<?= $property->zipcode ?>" class="input-field" required>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">State*</label>
                <input type="text" name="state_province" placeholder="Enter Property State" value="<?= $property->state_province ?>" class="input-field" required>
            </div>
            <div class="input-group-group">
                <label class="input-label">Country*</label>
                <input type="text" name="country" placeholder="Enter Property Country" value="<?= $property->country ?>" class="input-field" required>
            </div>
        </div>

        <h3 class="form-headers">Rental Information*</h3>

        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Purpose*</label>
                <select name="purpose" id="purpose" class="input-field" onchange="handlePurposeChange()">
                    <option value="">-- Select Purpose --</option>
                    <option value="Rent" <?= $property->purpose == 'Rent' ? 'selected' : '' ?>>For Rent</option>
                    <option value="Safeguard" <?= $property->purpose == 'Safeguard' ? 'selected' : '' ?>>For Safeguard (Security Purposes)</option>
                    <option value="Vacation_Rental" <?= $property->purpose == 'Vacation_Rental' ? 'selected' : '' ?>>Vacation Rental</option>
                </select>
            </div>
        </div>

        <!-- Section shown only for Rent -->
        <div class="input-group" id="rent-fields" style="display:none;">
            <div class="input-group-group">
                <label class="input-label">Rental Period*</label>
                <select name="rental_period" class="input-field">
                    <option value="Monthly" <?= $property->rental_period == 'Monthly' ? 'selected' : '' ?>>Monthly</option>
                    <option value="Annually" <?= $property->rental_period == 'Annually' ? 'selected' : '' ?>>Annually</option>
                    <option value="Daily" <?= $property->rental_period == 'Daily' ? 'selected' : '' ?>>Daily</option>
                </select>
            </div>
            <div class="input-group-group">
                <label class="input-label">Duration (in months)*</label>
                <input type="number" id="duration" name="duration" class="input-field" min="0" value="<?= $property->duration ?>" oninput="calculateRental()">
            </div>
            <div class="input-group-group">
                <label class="input-label">Rental Price*</label>
                <input type="number" name="rental_price" placeholder="Enter the rental price" value="<?= $property->rental_price ?>" class="input-field">
            </div>
        </div>


        <!-- Section shown for Safeguard or Vacation Rental -->
        <div class="input-group" id="other-fields" style="display:none;">
            <div class="input-group-group">
                <label class="input-label">Start Date*</label>
                <input type="date" id="start_date" name="start_date" class="input-field" value="<?= date('Y-m-d', strtotime($property->start_date)) ?>" onchange="updateEndDateLimit(); calculateRentalFromDates();" min="<?= date('Y-m-d') ?>">
            </div>
            <div class="input-group-group">
                <label class="input-label">End Date*</label>
                <input type="date" id="end_date" name="end_date" class="input-field" value="<?= date('Y-m-d', strtotime($property->end_date)) ?>" onchange="calculateRentalFromDates();">
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
                    <input type="text" name="year_built" placeholder="Enter Property Year Built" value="<?= $property->year_built ?>" class="input-field">
                </div>
                <div class="input-group-group">
                    <label class="input-label">Size Of Property In Square Roots*</label>
                    <input type="number" name="size_sqr_ft" placeholder="Enter Property Size" value="<?= $property->size_sqr_ft ?>" class="input-field">
                </div>
                <div class="input-group-group">
                    <label class="input-label">Number of Floors*</label>
                    <input type="number" name="number_of_floors" placeholder="Enter Number of floors" value="<?= $property->number_of_floors ?>" class="input-field">
                </div>
            </div>
            <div class="input-group-group">
                <label class="input-label">Floor Plan</label>
                <textarea name="floor_plan" placeholder="Enter a Description About The Property" class="input-field"><?= $property->floor_plan ?></textarea>
            </div>


            <h3 class="form-headers">Room Details</h3>
            <div class="input-group-group">
                <label class="input-label">No Of Units Of Property*</label>
                <input type="number" name="units" placeholder="Enter No Of Property Units" value="<?= $property->units ?>" class="input-field">
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Bedrooms*</label>
                    <input type="number" name="bedrooms" placeholder="Enter No Of Bedrooms" value="<?= $property->bedrooms ?>" class="input-field">
                </div>
                <div class="input-group-group">
                    <label class="input-label">Bathrooms*</label>
                    <input type="number" name="bathrooms" placeholder="Enter No of Bathrooms" value="<?= $property->bathrooms ?>" class="input-field">
                </div>
            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Kitchen*</label>
                    <input type="number" name="kitchen" placeholder="Enter No Of Kitchens" value="<?= $property->kitchen ?>" class="input-field">
                </div>
                <div class="input-group-group">
                    <label class="input-label">Living Rooms*</label>
                    <input type="number" name="living_room" placeholder="Enter No of Living Rooms" value="<?= $property->living_room ?>" class="input-field">
                </div>
            </div>


            <h3 class="form-headers">Furnishing Details</h3>
            <div class="input-group">
                <label class="input-label">Furnished*</label>
                <select name="furnished" class="input-field">
                    <option value="Fully Furnished" <?= $property->furnished == 'Fully Furnished' ? 'selected' : '' ?>>Fully Furnished</option>
                    <option value="Semi-Furnished" <?= $property->furnished == 'Semi-Furnished' ? 'selected' : '' ?>>Semi-Furnished</option>
                    <option value="Unfurnished" <?= $property->furnished == 'Unfurnished' ? 'selected' : '' ?>>Unfurnished</option>
                </select>

            </div>
            <div class="input-group-group">
                <label class="input-label">Furnished Description*</label>
                <textarea name="furniture_description" placeholder="Enter a Furnished Description" class="input-field"><?= $property->furniture_description ?></textarea>
            </div>



            <h3 class="form-headers">Parking Details</h3>
            <div class="input-group">
                <label class="input-label">Parking*</label>
                <select name="parking" class="input-field">
                    <option value="1" <?= $property->parking == '1' ? 'selected' : '' ?>>Yes</option>
                    <option value="0" <?= $property->parking == '0' ? 'selected' : '' ?>>No</option>
                </select>

            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Parking Slots*</label>
                    <input type="number" name="parking_slots" placeholder="Enter No Of Parking Slots" value="<?= $property->parking_slots ?>" class="input-field">
                </div>
                <div class="input-group-group">
                    <label class="input-label">Type Of Parking*</label>
                    <select name="type_of_parking" class="input-field">
                        <option value="Covered Garage" <?= $property->type_of_parking == 'Covered Garage' ? 'selected' : '' ?>>Covered Garage</option>
                        <option value="Open Parking" <?= $property->type_of_parking == 'Open Parking' ? 'selected' : '' ?>>Open Parking</option>
                        <option value="Street Parking" <?= $property->type_of_parking == 'Street Parking' ? 'selected' : '' ?>>Street Parking</option>
                        <option value="Carport" <?= $property->type_of_parking == 'Carport' ? 'selected' : '' ?>>Carport</option>
                        <option value="Underground Parking" <?= $property->type_of_parking == 'Underground Parking' ? 'selected' : '' ?>>Underground Parking</option>
                    </select>
                </div>
            </div>


            <h3 class="form-headers">Amenities</h3>
            <?php
            // Assuming the $property->utilities_included is a comma-separated string from the database
            $utilities_included = isset($property->utilities_included) ? explode(',', $property->utilities_included) : [];
            ?>
            <div class="input-group-group">
                <label class="input-label">Utilities Included*</label>
                <div class="checkbox-group checkbox-group-column extra-margin-botton">
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label">
                            <input type="checkbox" name="utilities_included[]" value="Electricity" <?= in_array('Electricity', $utilities_included) ? 'checked' : '' ?>> Electricity
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="utilities_included[]" value="Water Supply" <?= in_array('Water Supply', $utilities_included) ? 'checked' : '' ?>> Water Supply
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="utilities_included[]" value="Internet" <?= in_array('Internet', $utilities_included) ? 'checked' : '' ?>> Internet
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="utilities_included[]" value="Gas Connection" <?= in_array('Gas Connection', $utilities_included) ? 'checked' : '' ?>> Gas Connection
                        </label>
                    </div>
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label">
                            <input type="checkbox" name="utilities_included[]" value="Cable TV" <?= in_array('Cable TV', $utilities_included) ? 'checked' : '' ?>> Cable TV
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="utilities_included[]" value="Solar Power" <?= in_array('Solar Power', $utilities_included) ? 'checked' : '' ?>> Solar Power
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="utilities_included[]" value="Backup Generator" <?= in_array('Backup Generator', $utilities_included) ? 'checked' : '' ?>> Backup Generator
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="utilities_included[]" value="Waste Disposal" <?= in_array('Waste Disposal', $utilities_included) ? 'checked' : '' ?>> Waste Disposal
                        </label>
                    </div>
                </div>
            </div>



            <div class="input-group-group">
                <label class="input-label">Additional Utilites*</label>
                <textarea name="additional_utilities" placeholder="Enter a Description about additional Utilities" class="input-field"><?= $property->additional_utilities ?></textarea>
            </div>


            <h3 class="form-headers">Security Details</h3>
            <?php
            // Assuming the $property->security_features is a comma-separated string from the database
            $security_features = isset($property->security_features) ? explode(',', $property->security_features) : [];
            ?>

            <div class="input-group-group">
                <div class="checkbox-group checkbox-group-column extra-margin-botton">
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label">
                            <input type="checkbox" name="security_features[]" value="CCTV" <?= in_array('CCTV', $security_features) ? 'checked' : '' ?>> CCTV
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="security_features[]" value="Security Guards" <?= in_array('Security Guards', $security_features) ? 'checked' : '' ?>> Security Guards
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="security_features[]" value="Intercom System" <?= in_array('Intercom System', $security_features) ? 'checked' : '' ?>> Intercom System
                        </label>
                    </div>
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label">
                            <input type="checkbox" name="security_features[]" value="Access Control" <?= in_array('Access Control', $security_features) ? 'checked' : '' ?>> Access Control
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="security_features[]" value="Fire Alarm" <?= in_array('Fire Alarm', $security_features) ? 'checked' : '' ?>> Fire Alarm
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="security_features[]" value="Gated Community" <?= in_array('Gated Community', $security_features) ? 'checked' : '' ?>> Gated Community
                        </label>
                    </div>
                </div>
            </div>




            <h3 class="form-headers">Additional Amenities*</h3>
            <?php
            // Assuming $property->additional_amenities contains a comma-separated string
            $additional_amenities = isset($property->additional_amenities) ? explode(',', $property->additional_amenities) : [];
            ?>

            <div class="input-group-group">
                <div class="checkbox-group checkbox-group-column extra-margin-botton">
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label">
                            <input type="checkbox" name="additional_amenities[]" value="Swimming Pool" <?= in_array('Swimming Pool', $additional_amenities) ? 'checked' : '' ?>> Swimming Pool
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="additional_amenities[]" value="Gym" <?= in_array('Gym', $additional_amenities) ? 'checked' : '' ?>> Gym
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="additional_amenities[]" value="Garden" <?= in_array('Garden', $additional_amenities) ? 'checked' : '' ?>> Garden
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="additional_amenities[]" value="Elevator" <?= in_array('Elevator', $additional_amenities) ? 'checked' : '' ?>> Elevator
                        </label>
                    </div>
                    <div class="checkbox-group checkbox-group-row">
                        <label class="inline-label">
                            <input type="checkbox" name="additional_amenities[]" value="Play Area" <?= in_array('Play Area', $additional_amenities) ? 'checked' : '' ?>> Play Area
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="additional_amenities[]" value="Clubhouse" <?= in_array('Clubhouse', $additional_amenities) ? 'checked' : '' ?>> Clubhouse
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="additional_amenities[]" value="Jogging Track" <?= in_array('Jogging Track', $additional_amenities) ? 'checked' : '' ?>> Jogging Track
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="additional_amenities[]" value="BBQ Area" <?= in_array('BBQ Area', $additional_amenities) ? 'checked' : '' ?>> BBQ Area
                        </label>
                    </div>
                </div>
            </div>


            <h3 class="form-headers">Special Instructions*</h3>
            <?php
            // Assuming $property->special_instructions contains a comma-separated string
            $special_instructions = isset($property->special_instructions) ? explode(',', $property->special_instructions) : [];
            ?>

            <div class="input-group">
                <div class="checkbox-group checkbox-group-column">
                    <label class="inline-label">
                        <input type="checkbox" name="special_instructions[]" value="No_Pets_Allowed" <?= in_array('No_Pets_Allowed', $special_instructions) ? 'checked' : '' ?>> No Pets Allowed
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="special_instructions[]" value="No_Smoking_Inside_the_Property" <?= in_array('No_Smoking_Inside_the_Property', $special_instructions) ? 'checked' : '' ?>> No Smoking Inside the Property
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="special_instructions[]" value="Suitable_for_Families_Only" <?= in_array('Suitable_for_Families_Only', $special_instructions) ? 'checked' : '' ?>> Suitable for Families Only
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="special_instructions[]" value="No_Loud_Music_or_Parties" <?= in_array('No_Loud_Music_or_Parties', $special_instructions) ? 'checked' : '' ?>> No Loud Music or Parties
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="special_instructions[]" value="Maintenance_Fee_Included" <?= in_array('Maintenance_Fee_Included', $special_instructions) ? 'checked' : '' ?>> Maintenance Fee Included
                    </label>
                </div>
                <div class="checkbox-group checkbox-group-column">
                    <label class="inline-label">
                        <input type="checkbox" name="special_instructions[]" value="Tenant_Responsible_for_Utilities" <?= in_array('Tenant_Responsible_for_Utilities', $special_instructions) ? 'checked' : '' ?>> Tenant Responsible for Utilities
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="special_instructions[]" value="Lease_Renewal_Option_Available" <?= in_array('Lease_Renewal_Option_Available', $special_instructions) ? 'checked' : '' ?>> Lease Renewal Option Available
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="special_instructions[]" value="Immediate_Move_In_Available" <?= in_array('Immediate_Move_In_Available', $special_instructions) ? 'checked' : '' ?>> Immediate Move-In Available
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="special_instructions[]" value="Background_Check_Required_for_Tenants" <?= in_array('Background_Check_Required_for_Tenants', $special_instructions) ? 'checked' : '' ?>> Background Check Required for Tenants
                    </label>
                </div>
            </div>

        </div>



        <h3 class="form-headers">Owner Information*</h3>
        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Owner Name*</label>
                <input type="text" name="owner_name" placeholder="Enter the Owner Name" value="<?= $property->owner_name ?>" class="input-field">
            </div>
            <div class="input-group-group">
                <label class="input-label">Owner Email*</label>
                <input type="text" name="owner_email" placeholder="Enter the Owner Email" value="<?= $property->owner_email ?>" class="input-field">
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Owner Contact Number*</label>
                <input type="text" name="owner_phone" placeholder="Enter the Owner Contact Number" value="<?= $property->owner_phone ?>" class="input-field">
            </div>
            <div class="input-group-group">
                <label class="input-label">Owner Additional Contact*</label>
                <input type="text" name="additional_contact" placeholder="Enter a Additional Contact" value="<?= $property->additional_contact ?>" class="input-field">
            </div>
        </div>



        <h3 class="form-headers">Legal Details*</h3>
        <?php
        // Assuming $property->legal_details contains a comma-separated string
        $legal_details = isset($property->legal_details) ? explode(',', $property->legal_details) : [];
        ?>

        <div class="input-group">
            <div class="checkbox-group checkbox-group-column">
                <label class="inline-label">
                    <input type="checkbox" name="legal_details[]" value="Property_Ownership_Verified" <?= in_array('Property_Ownership_Verified', $legal_details) ? 'checked' : '' ?>> Property Ownership Verified
                </label>
                <label class="inline-label">
                    <input type="checkbox" name="legal_details[]" value="Property_Free_from_Legal_Disputes" <?= in_array('Property_Free_from_Legal_Disputes', $legal_details) ? 'checked' : '' ?>> Property Free from Legal Disputes
                </label>
                <label class="inline-label">
                    <input type="checkbox" name="legal_details[]" value="All_Taxes_Paid_Up_to_Date" <?= in_array('All_Taxes_Paid_Up_to_Date', $legal_details) ? 'checked' : '' ?>> All Taxes Paid Up to Date
                </label>
                <label class="inline-label">
                    <input type="checkbox" name="legal_details[]" value="Rental_Agreement_Draft_Available" <?= in_array('Rental_Agreement_Draft_Available', $legal_details) ? 'checked' : '' ?>> Rental Agreement Draft Available
                </label>
                <label class="inline-label">
                    <input type="checkbox" name="legal_details[]" value="Property_Insured" <?= in_array('Property_Insured', $legal_details) ? 'checked' : '' ?>> Property Insured
                </label>
            </div>
            <div class="checkbox-group checkbox-group-column">
                <label class="inline-label">
                    <input type="checkbox" name="legal_details[]" value="Compliance_with_Local_Housing_Laws" <?= in_array('Compliance_with_Local_Housing_Laws', $legal_details) ? 'checked' : '' ?>> Compliance with Local Housing Laws
                </label>
                <label class="inline-label">
                    <input type="checkbox" name="legal_details[]" value="Tenant_Screening_Required" <?= in_array('Tenant_Screening_Required', $legal_details) ? 'checked' : '' ?>> Tenant Screening Required
                </label>
                <label class="inline-label">
                    <input type="checkbox" name="legal_details[]" value="Lease_Agreement_Must_Be_Signed" <?= in_array('Lease_Agreement_Must_Be_Signed', $legal_details) ? 'checked' : '' ?>> Lease Agreement Must Be Signed
                </label>
                <label class="inline-label">
                    <input type="checkbox" name="legal_details[]" value="Security_Deposit_Refundable" <?= in_array('Security_Deposit_Refundable', $legal_details) ? 'checked' : '' ?>> Security Deposit Refundable (Subject to Conditions)
                </label>
            </div>
        </div>






        <!-- <h3 class="form-headers">Photos*</h3>

        <label class="input-label">Upload Property Images (Max 6)*</label>
        <div class="owner-addProp-file-upload">
            <input type="file" name="property_images[]" id="property_images" class="input-field" multiple
                accept=".png, .jpg, .jpeg" data-max-files="6" onchange="handleFileSelect(event)">
            <div class="owner-addProp-upload-area">
                <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                <p class="upload-area-no-margin">Drop your New files here.Previous Images will be deleted!</p>
                <button type="button" class="primary-btn" onclick="document.getElementById('property_images').click()">Choose File</button>
            </div>
        </div>

        <div id="uploaded-files" class="owner-addProp-uploaded-files">
        </div> -->

        <hr>

        <div class="items-inline">
            <input type="checkbox" name="terms" required />
            <p>By Clicking, I Agree To Terms & Conditions.</p>
        </div>
        <div class="buttons-to-right">
            <button type="submit" class="primary-btn">Update</button>
        </div>


    </div>
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
    // function handlePurposeChange() {
    //     const purpose = document.getElementById('purpose').value;
    //     const rentFields = document.getElementById('rent-fields');
    //     const otherFields = document.getElementById('other-fields');
    //     const restOfFeilds = document.getElementById('rest-of-fields');

    //     if (purpose === 'Rent') {
    //         rentFields.style.display = 'flex';
    //         otherFields.style.display = 'none';
    //         restOfFeilds.style.display = 'block';
    //     } else if (purpose === 'Safeguard' || purpose === 'Vacation_Rental') {
    //         rentFields.style.display = 'none';
    //         otherFields.style.display = 'flex';
    //         restOfFeilds.style.display = 'none';
    //     } else {
    //         rentFields.style.display = 'none';
    //         otherFields.style.display = 'none';
    //     }
    // }

    function handlePurposeChange() {
        const purpose = document.getElementById('purpose').value;
        const rentFields = document.getElementById('rent-fields');
        const otherFields = document.getElementById('other-fields');
        const restOfFeilds = document.getElementById('rest-of-fields');

        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        if (purpose === 'Rent') {
            rentFields.style.display = 'flex';
            otherFields.style.display = 'none';
            restOfFeilds.style.display = 'block';

            // ✅ Remove min or required if not needed
            startDate.removeAttribute('required');
            endDate.removeAttribute('required');
            startDate.removeAttribute('min');
            endDate.removeAttribute('min');
        } else if (purpose === 'Safeguard' || purpose === 'Vacation_Rental') {
            rentFields.style.display = 'none';
            otherFields.style.display = 'flex';
            restOfFeilds.style.display = 'none';

            // ✅ Add back validation
            startDate.setAttribute('required', 'true');
            endDate.setAttribute('required', 'true');

            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            startDate.setAttribute('min', today);
        } else {
            rentFields.style.display = 'none';
            otherFields.style.display = 'none';
        }
    }
</script>

<script>
    function calculateRental() {
        const durationInput = document.getElementById('duration');
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
<!-- 

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
</script> -->

<script>
    function updateEndDateLimit() {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const purpose = document.getElementById('purpose')?.value;

        // ✅ Skip validation for "Rent" purpose
        if (purpose === 'Rent') return;

        if (startInput.value) {
            const startDate = new Date(startInput.value);
            const minEndDate = new Date(startDate);
            minEndDate.setDate(startDate.getDate() + 1);

            const yyyy = minEndDate.getFullYear();
            const mm = String(minEndDate.getMonth() + 1).padStart(2, '0');
            const dd = String(minEndDate.getDate()).padStart(2, '0');
            endInput.min = `${yyyy}-${mm}-${dd}`;

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
        const purpose = document.getElementById('purpose')?.value;

        if (startInput.value && endInput.value) {
            const startDate = new Date(startInput.value);
            const endDate = new Date(endInput.value);

            // ✅ Only check date logic if NOT "Rent"
            if (purpose !== 'Rent') {
                if (endDate <= startDate) {
                    totalDisplay.value = "End date must be after start date";
                    return;
                }
            }

            const diffTime = endDate - startDate;
            const duration = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            const total = duration * baseRate;

            totalDisplay.value = total.toLocaleString('en-US', {
                style: 'currency',
                currency: 'LKR'
            });
        } else {
            totalDisplay.value = "";
        }
    }
</script>




<script src="<?= ROOT ?>/assets/js/property/addproperty.js"></script>
<?php require_once 'ownerFooter.view.php'; ?>