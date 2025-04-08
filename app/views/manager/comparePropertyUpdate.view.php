<?php require_once 'managerHeader.view.php'; ?>

<?php
function check_equality($property, $propertyUpdate, $field)
{
    if ($property->$field == $propertyUpdate->$field) {
        return true;
    } else {
        return false;
    }
}
?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome/propertymanagement/requestapproval'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Compare Details on <span style="color: var(--green-color);"><?= $property->name ?></span></h2>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Call the function to set the initial state based on the current "purpose" value
        handlePurposeChange();
        handlePurposeChangeUpdated();

        // Trigger any other necessary calculations for Rent or other purposes
        calculateRental();
        calculateRentalUpdated();
    });
</script>


<form>
    <div class="owner-addProp-container">

        <!-- Previuss Property Details -->

        <div class="owner-addProp-form-left">

            <h3 class="form-headers no-top-border"><span style="color: var(--primary-color)">Previous </span>Property Details</h3>
            <label class="input-label">Name Of Property*</label>
            <input type="text" name="name" value="<?= $property->name ?>" class="input-field" disabled>

            <label class="input-label">Type*</label>
            <input type="text" name="type" value="<?= $property->type ?>" class="input-field" disabled>


            <label class="input-label">Description*</label>
            <textarea name="description" placeholder="Write About Property" class="input-field" disabled><?= $property->description ?></textarea>


            <h3 class="form-headers">Basic Property Information</h3>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Address*</label>
                    <input type="text" name="address" placeholder="Enter Address" value="<?= $property->address ?>" class="input-field" disabled>
                </div>
            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">City*</label>
                    <input type="text" name="city" placeholder="Enter Property City" value="<?= $property->city ?>" class="input-field" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Zip Code*</label>
                    <input type="text" name="zipcode" placeholder="Enter Property Zip Code" value="<?= $property->zipcode ?>" class="input-field" disabled>
                </div>
            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">State*</label>
                    <input type="text" name="state_province" placeholder="Enter Property State" value="<?= $property->state_province ?>" class="input-field" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Country*</label>
                    <input type="text" name="country" placeholder="Enter Property Country" value="<?= $property->country ?>" class="input-field" disabled>
                </div>
            </div>

            <h3 class="form-headers">Rental Information*</h3>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Purpose*</label>
                    <input type="text" name="purpose" value="<?= $property->purpose ?>" class="input-field" disabled>
                </div>
            </div>

            <!-- Section shown only for Rent -->
            <div class="input-group" id="rent-fields" style="<?= ($property->purpose == "Rent") ? "display:block;" : "display:none;" ?>">
                <div class="input-group-group">
                    <label class="input-label">Rental Period*</label>
                    <input type="text" name="rental_period" value="<?= $property->rental_period ?>" class="input-field" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Duration (in months)*</label>
                    <input type="number" id="duration" name="duration" class="input-field" min="1" value="<?= $property->duration ?>" oninput="calculateRental()" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Rental Price*</label>
                    <input type="number" name="rental_price" placeholder="Enter the rental price" value="<?= $property->rental_price ?>" class="input-field" disabled>
                </div>
            </div>


            <!-- Section shown for Safeguard or Vacation Rental -->
            <div class="input-group" id="other-fields" style="<?= ($property->purpose == "Rent") ? "display:none;" : "display:block;" ?>">
                <div class="input-group-group">
                    <label class="input-label">Start Date*</label>
                    <input type="date" id="start_date" name="start_date" class="input-field" value="<?= $property->start_date ?>" onchange="updateEndDateLimit(); calculateRentalFromDates();" min="<?= date('Y-m-d') ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">End Date*</label>
                    <input type="date" id="end_date" name="end_date" class="input-field" value="<?= $property->end_date ?>" onchange="calculateRentalFromDates();" disabled>
                </div>
            </div>



            <div id="rest-of-fields" style="<?= ($property->purpose == "Rent") ? "display:block;" : "display:none;" ?>">

                <h3 class="form-headers">Property Specifications</h3>
                <div class="input-group">
                    <div class="input-group-group">
                        <label class="input-label">Year Built*</label>
                        <input type="text" name="year_built" placeholder="Enter Property Year Built" value="<?= $property->year_built ?>" class="input-field" disabled>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Size Of Property In Square Roots*</label>
                        <input type="number" name="size_sqr_ft" placeholder="Enter Property Size" value="<?= $property->size_sqr_ft ?>" class="input-field" disabled>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Number of Floors*</label>
                        <input type="number" name="number_of_floors" placeholder="Enter Number of floors" value="<?= $property->number_of_floors ?>" class="input-field" disabled>
                    </div>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Floor Plan</label>
                    <textarea name="floor_plan" placeholder="Enter a Description About The Property" class="input-field" disabled><?= $property->floor_plan ?></textarea>
                </div>


                <h3 class="form-headers">Room Details</h3>
                <div class="input-group-group">
                    <label class="input-label">No Of Units Of Property*</label>
                    <input type="number" name="units" placeholder="Enter No Of Property Units" value="<?= $property->units ?>" class="input-field" disabled>
                </div>

                <div class="input-group">
                    <div class="input-group-group">
                        <label class="input-label">Bedrooms*</label>
                        <input type="number" name="bedrooms" placeholder="Enter No Of Bedrooms" value="<?= $property->bedrooms ?>" class="input-field" disabled>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Bathrooms*</label>
                        <input type="number" name="bathrooms" placeholder="Enter No of Bathrooms" value="<?= $property->bathrooms ?>" class="input-field" disabled>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-group-group">
                        <label class="input-label">Kitchen*</label>
                        <input type="number" name="kitchen" placeholder="Enter No Of Kitchens" value="<?= $property->kitchen ?>" class="input-field" disabled>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Living Rooms*</label>
                        <input type="number" name="living_room" placeholder="Enter No of Living Rooms" value="<?= $property->living_room ?>" class="input-field" disabled>
                    </div>
                </div>


                <h3 class="form-headers">Furnishing Details</h3>
                <div class="input-group">
                    <label class="input-label">Furnished*</label>
                    <select name="furnished" class="input-field" disabled>
                        <option value="Fully Furnished" <?= $property->furnished == 'Fully Furnished' ? 'selected' : '' ?>>Fully Furnished</option>
                        <option value="Semi-Furnished" <?= $property->furnished == 'Semi-Furnished' ? 'selected' : '' ?>>Semi-Furnished</option>
                        <option value="Unfurnished" <?= $property->furnished == 'Unfurnished' ? 'selected' : '' ?>>Unfurnished</option>
                    </select>

                </div>
                <div class="input-group-group">
                    <label class="input-label">Furnished Description*</label>
                    <textarea name="furniture_description" placeholder="Enter a Furnished Description" class="input-field" disabled><?= $property->furniture_description ?></textarea>
                </div>



                <h3 class="form-headers">Parking Details</h3>
                <div class="input-group">
                    <label class="input-label">Parking*</label>
                    <select name="parking" class="input-field" disabled>
                        <option value="1" <?= $property->parking == '1' ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= $property->parking == '0' ? 'selected' : '' ?>>No</option>
                    </select>

                </div>
                <div class="input-group">
                    <div class="input-group-group">
                        <label class="input-label">Parking Slots*</label>
                        <input type="number" name="parking_slots" placeholder="Enter No Of Parking Slots" value="<?= $property->parking_slots ?>" class="input-field" disabled>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Type Of Parking*</label>
                        <select name="type_of_parking" class="input-field" disabled>
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
                                <input type="checkbox" name="utilities_included[]" value="Electricity" <?= in_array('Electricity', $utilities_included) ? 'checked' : '' ?> disabled> Electricity
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Water Supply" <?= in_array('Water Supply', $utilities_included) ? 'checked' : '' ?> disabled> Water Supply
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Internet" <?= in_array('Internet', $utilities_included) ? 'checked' : '' ?> disabled> Internet
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Gas Connection" <?= in_array('Gas Connection', $utilities_included) ? 'checked' : '' ?> disabled> Gas Connection
                            </label>
                        </div>
                        <div class="checkbox-group checkbox-group-row">
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Cable TV" <?= in_array('Cable TV', $utilities_included) ? 'checked' : '' ?> disabled> Cable TV
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Solar Power" <?= in_array('Solar Power', $utilities_included) ? 'checked' : '' ?> disabled> Solar Power
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Backup Generator" <?= in_array('Backup Generator', $utilities_included) ? 'checked' : '' ?> disabled> Backup Generator
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Waste Disposal" <?= in_array('Waste Disposal', $utilities_included) ? 'checked' : '' ?> disabled> Waste Disposal
                            </label>
                        </div>
                    </div>
                </div>



                <div class="input-group-group">
                    <label class="input-label">Additional Utilites*</label>
                    <textarea name="additional_utilities" placeholder="Enter a Description about additional Utilities" class="input-field" disabled><?= $property->additional_utilities ?></textarea>
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
                                <input type="checkbox" name="security_features[]" value="CCTV" <?= in_array('CCTV', $security_features) ? 'checked' : '' ?> disabled> CCTV
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="security_features[]" value="Security Guards" <?= in_array('Security Guards', $security_features) ? 'checked' : '' ?> disabled> Security Guards
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="security_features[]" value="Intercom System" <?= in_array('Intercom System', $security_features) ? 'checked' : '' ?> disabled> Intercom System
                            </label>
                        </div>
                        <div class="checkbox-group checkbox-group-row">
                            <label class="inline-label">
                                <input type="checkbox" name="security_features[]" value="Access Control" <?= in_array('Access Control', $security_features) ? 'checked' : '' ?> disabled> Access Control
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="security_features[]" value="Fire Alarm" <?= in_array('Fire Alarm', $security_features) ? 'checked' : '' ?> disabled> Fire Alarm
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="security_features[]" value="Gated Community" <?= in_array('Gated Community', $security_features) ? 'checked' : '' ?> disabled> Gated Community
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
                                <input type="checkbox" name="additional_amenities[]" value="Swimming Pool" <?= in_array('Swimming Pool', $additional_amenities) ? 'checked' : '' ?> disabled> Swimming Pool
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Gym" <?= in_array('Gym', $additional_amenities) ? 'checked' : '' ?> disabled> Gym
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Garden" <?= in_array('Garden', $additional_amenities) ? 'checked' : '' ?> disabled> Garden
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Elevator" <?= in_array('Elevator', $additional_amenities) ? 'checked' : '' ?> disabled> Elevator
                            </label>
                        </div>
                        <div class="checkbox-group checkbox-group-row">
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Play Area" <?= in_array('Play Area', $additional_amenities) ? 'checked' : '' ?> disabled> Play Area
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Clubhouse" <?= in_array('Clubhouse', $additional_amenities) ? 'checked' : '' ?> disabled> Clubhouse
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Jogging Track" <?= in_array('Jogging Track', $additional_amenities) ? 'checked' : '' ?> disabled> Jogging Track
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="BBQ Area" <?= in_array('BBQ Area', $additional_amenities) ? 'checked' : '' ?> disabled> BBQ Area
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
                            <input type="checkbox" name="special_instructions[]" value="No_Pets_Allowed" <?= in_array('No_Pets_Allowed', $special_instructions) ? 'checked' : '' ?> disabled> No Pets Allowed
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="No_Smoking_Inside_the_Property" <?= in_array('No_Smoking_Inside_the_Property', $special_instructions) ? 'checked' : '' ?> disabled> No Smoking Inside the Property
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Suitable_for_Families_Only" <?= in_array('Suitable_for_Families_Only', $special_instructions) ? 'checked' : '' ?> disabled> Suitable for Families Only
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="No_Loud_Music_or_Parties" <?= in_array('No_Loud_Music_or_Parties', $special_instructions) ? 'checked' : '' ?> disabled> No Loud Music or Parties
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Maintenance_Fee_Included" <?= in_array('Maintenance_Fee_Included', $special_instructions) ? 'checked' : '' ?> disabled> Maintenance Fee Included
                        </label>
                    </div>
                    <div class="checkbox-group checkbox-group-column">
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Tenant_Responsible_for_Utilities" <?= in_array('Tenant_Responsible_for_Utilities', $special_instructions) ? 'checked' : '' ?> disabled> Tenant Responsible for Utilities
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Lease_Renewal_Option_Available" <?= in_array('Lease_Renewal_Option_Available', $special_instructions) ? 'checked' : '' ?> disabled> Lease Renewal Option Available
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Immediate_Move_In_Available" <?= in_array('Immediate_Move_In_Available', $special_instructions) ? 'checked' : '' ?> disabled> Immediate Move-In Available
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Background_Check_Required_for_Tenants" <?= in_array('Background_Check_Required_for_Tenants', $special_instructions) ? 'checked' : '' ?> disabled> Background Check Required for Tenants
                        </label>
                    </div>
                </div>

            </div>



            <h3 class="form-headers">Owner Information*</h3>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Owner Name*</label>
                    <input type="text" name="owner_name" placeholder="Enter the Owner Name" value="<?= $property->owner_name ?>" class="input-field" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Owner Email*</label>
                    <input type="text" name="owner_email" placeholder="Enter the Owner Email" value="<?= $property->owner_email ?>" class="input-field" disabled>
                </div>
            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Owner Contact Number*</label>
                    <input type="text" name="owner_phone" placeholder="Enter the Owner Contact Number" value="<?= $property->owner_phone ?>" class="input-field" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Owner Additional Contact*</label>
                    <input type="text" name="additional_contact" placeholder="Enter a Additional Contact" value="<?= $property->additional_contact ?>" class="input-field" disabled>
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
                        <input type="checkbox" name="legal_details[]" value="Property_Ownership_Verified" <?= in_array('Property_Ownership_Verified', $legal_details) ? 'checked' : '' ?> disabled> Property Ownership Verified
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Property_Free_from_Legal_Disputes" <?= in_array('Property_Free_from_Legal_Disputes', $legal_details) ? 'checked' : '' ?> disabled> Property Free from Legal Disputes
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="All_Taxes_Paid_Up_to_Date" <?= in_array('All_Taxes_Paid_Up_to_Date', $legal_details) ? 'checked' : '' ?> disabled> All Taxes Paid Up to Date
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Rental_Agreement_Draft_Available" <?= in_array('Rental_Agreement_Draft_Available', $legal_details) ? 'checked' : '' ?> disabled> Rental Agreement Draft Available
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Property_Insured" <?= in_array('Property_Insured', $legal_details) ? 'checked' : '' ?> disabled> Property Insured
                    </label>
                </div>
                <div class="checkbox-group checkbox-group-column">
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Compliance_with_Local_Housing_Laws" <?= in_array('Compliance_with_Local_Housing_Laws', $legal_details) ? 'checked' : '' ?> disabled> Compliance with Local Housing Laws
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Tenant_Screening_Required" <?= in_array('Tenant_Screening_Required', $legal_details) ? 'checked' : '' ?> disabled> Tenant Screening Required
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Lease_Agreement_Must_Be_Signed" <?= in_array('Lease_Agreement_Must_Be_Signed', $legal_details) ? 'checked' : '' ?> disabled> Lease Agreement Must Be Signed
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Security_Deposit_Refundable" <?= in_array('Security_Deposit_Refundable', $legal_details) ? 'checked' : '' ?> disabled> Security Deposit Refundable (Subject to Conditions)
                    </label>
                </div>
            </div>

        </div>


        <!-- Updated Property Details -->


        <div class="owner-addProp-form-right">

            <h3 class="form-headers no-top-border"><span style="color: var(--red-color)">Updated </span>Property Details</h3>
            <label class="input-label">Name Of Property*</label>
            <input type="text" name="name" placeholder="Enter Property Name" value="<?= $propertyUpdate->name ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'name') ? '' : 'not-equal' ?>" disabled>

            <label class="input-label">Type*</label>
            <input type="text" name="type" value="<?= $propertyUpdate->type ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'type') ? '' : 'not-equal' ?>" disabled>


            <label class="input-label">Description*</label>
            <textarea name="description" placeholder="Write About Property" class="input-field <?= check_equality($property, $propertyUpdate, 'description') ? '' : 'not-equal' ?>" disabled><?= $propertyUpdate->description ?></textarea>


            <h3 class="form-headers">Basic Property Information</h3>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Address*</label>
                    <input type="text" name="address" placeholder="Enter Address" value="<?= $propertyUpdate->address ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'address') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">City*</label>
                    <input type="text" name="city" placeholder="Enter Property City" value="<?= $propertyUpdate->city ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'city') ? '' : 'not-equal' ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Zip Code*</label>
                    <input type="text" name="zipcode" placeholder="Enter Property Zip Code" value="<?= $propertyUpdate->zipcode ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'zipcode') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">State*</label>
                    <input type="text" name="state_province" placeholder="Enter Property State" value="<?= $propertyUpdate->state_province ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'state_province') ? '' : 'not-equal' ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Country*</label>
                    <input type="text" name="country" placeholder="Enter Property Country" value="<?= $propertyUpdate->country ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'country') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>

            <h3 class="form-headers">Rental Information*</h3>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Purpose*</label>
                    <input type="text" name="purpose" value="<?= $propertyUpdate->purpose ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'purpose') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>

            <!-- Section shown only for Rent -->
            <div class="input-group" id="rent-fields-updated" style="<?= ($property->purpose == "Rent") ? "display:block;" : "display:none;" ?>">
                <div class="input-group-group">
                    <label class="input-label">Rental Period*</label>
                    <input type="text" name="rental_period" value="<?= $propertyUpdate->rental_period ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'rental_period') ? '' : 'not-equal' ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Duration (in months)*</label>
                    <input type="number" id="duration" name="duration" class="input-field <?= check_equality($property, $propertyUpdate, 'duration') ? '' : 'not-equal' ?>" disabled min="1" value="<?= $propertyUpdate->duration ?>" oninput="calculateRental()">
                </div>
                <div class="input-group-group">
                    <label class="input-label">Rental Price*</label>
                    <input type="number" name="rental_price" placeholder="Enter the rental price" value="<?= $propertyUpdate->rental_price ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'rental_price') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>


            <!-- Section shown for Safeguard or Vacation Rental -->
            <div class="input-group" id="other-fields-updated" style="<?= ($property->purpose == "Rent") ? "display:none;" : "display:none;" ?>">
                <div class="input-group-group">
                    <label class="input-label">Start Date*</label>
                    <input type="date" id="start_date-updated" name="start_date" class="input-field <?= check_equality($property, $propertyUpdate, 'start_date') ? '' : 'not-equal' ?>" disabled value="<?= date('Y-m-d', strtotime($propertyUpdate->start_date)) ?>" onchange="updateEndDateLimit(); calculateRentalFromDates();" min="<?= date('Y-m-d') ?>">
                </div>
                <div class="input-group-group">
                    <label class="input-label">End Date*</label>
                    <input type="date" id="end_date-updated" name="end_date" class="input-field <?= check_equality($property, $propertyUpdate, 'end_date') ? '' : 'not-equal' ?>" disabled value="<?= date('Y-m-d', strtotime($propertyUpdate->end_date)) ?>"" onchange=" calculateRentalFromDates();">
                </div>
            </div>



            <div id="rest-of-fields-updated" style="<?= ($property->purpose == "Rent") ? "display:block;" : "display:none;" ?>">

                <h3 class="form-headers">Property Specifications</h3>
                <div class="input-group">
                    <div class="input-group-group">
                        <label class="input-label">Year Built*</label>
                        <input type="text" name="year_built" value="<?= $propertyUpdate->year_built ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'year_built') ? '' : 'not-equal' ?>" disabled>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Size Of Property In Square Roots*</label>
                        <input type="number" name="size_sqr_ft" value="<?= $propertyUpdate->size_sqr_ft ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'size_sqr_ft') ? '' : 'not-equal' ?>" disabled>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Number of Floors*</label>
                        <input type="number" name="number_of_floors" value="<?= $propertyUpdate->number_of_floors ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'number_of_floors') ? '' : 'not-equal' ?>" disabled>
                    </div>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Floor Plan</label>
                    <textarea name="floor_plan" class="input-field <?= check_equality($property, $propertyUpdate, 'floor_plan') ? '' : 'not-equal' ?>" disabled><?= $propertyUpdate->floor_plan ?></textarea>
                </div>


                <h3 class="form-headers">Room Details</h3>
                <div class="input-group-group">
                    <label class="input-label">No Of Units Of Property*</label>
                    <input type="number" name="units" value="<?= $propertyUpdate->units ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'units') ? '' : 'not-equal' ?>" disabled>
                </div>

                <div class="input-group">
                    <div class="input-group-group">
                        <label class="input-label">Bedrooms*</label>
                        <input type="number" name="bedrooms" value="<?= $propertyUpdate->bedrooms ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'bedrooms') ? '' : 'not-equal' ?>" disabled>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Bathrooms*</label>
                        <input type="number" name="bathrooms" value="<?= $propertyUpdate->bathrooms ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'bathrooms') ? '' : 'not-equal' ?>" disabled>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-group-group">
                        <label class="input-label">Kitchen*</label>
                        <input type="number" name="kitchen" value="<?= $propertyUpdate->kitchen ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'kitchen') ? '' : 'not-equal' ?>" disabled>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Living Rooms*</label>
                        <input type="number" name="living_room" value="<?= $propertyUpdate->living_room ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'living_room') ? '' : 'not-equal' ?>" disabled>
                    </div>
                </div>


                <h3 class="form-headers">Furnishing Details</h3>
                <div class="input-group">
                    <label class="input-label">Furnished*</label>
                    <input type="number" name="furnished" value="<?= $propertyUpdate->furnished ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'furnished') ? '' : 'not-equal' ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Furnished Description*</label>
                    <textarea name="furniture_description" class="input-field <?= check_equality($property, $propertyUpdate, 'furniture_description') ? '' : 'not-equal' ?>" disabled><?= $propertyUpdate->furniture_description ?></textarea>
                </div>



                <h3 class="form-headers">Parking Details</h3>
                <div class="input-group">
                    <label class="input-label">Parking*</label>
                    <select name="parking" class="input-field <?= check_equality($property, $propertyUpdate, 'parking') ? '' : 'not-equal' ?>" disabled>
                        <option value="1" <?= $propertyUpdate->parking == '1' ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= $propertyUpdate->parking == '0' ? 'selected' : '' ?>>No</option>
                    </select>

                </div>
                <div class="input-group">
                    <div class="input-group-group">
                        <label class="input-label">Parking Slots*</label>
                        <input type="number" name="parking_slots" value="<?= $propertyUpdate->parking_slots ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'parking_slots') ? '' : 'not-equal' ?>" disabled>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Type Of Parking*</label>
                        <input type="text" name="type_of_parking" value="<?= $propertyUpdate->type_of_parking ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'type_of_parking') ? '' : 'not-equal' ?>" disabled>
                    </div>
                </div>


                <h3 class="form-headers">Amenities</h3>
                <?php
                // Assuming the $property->utilities_included is a comma-separated string from the database
                $utilities_included = isset($propertyUpdate->utilities_included) ? explode(',', $propertyUpdate->utilities_included) : [];
                $utilities_included_update = isset($property->utilities_included) ? explode(',', $property->utilities_included) : [];
                ?>
                <div class="input-group-group">
                    <label class="input-label">Utilities Included*</label>
                    <div class="checkbox-group checkbox-group-column extra-margin-botton">
                        <div class="checkbox-group checkbox-group-row">
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Electricity" <?= checkboxesStates($utilities_included_update , $utilities_included , 'Electricity') ?> disabled> Electricity
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Water Supply" <?= checkboxesStates($utilities_included_update , $utilities_included , 'Water Supply') ?> disabled> Water Supply
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Internet" <?= checkboxesStates($utilities_included_update , $utilities_included , 'Internet') ?> disabled> Internet
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Gas Connection" <?= checkboxesStates($utilities_included_update , $utilities_included , 'Gas Connection') ?> disabled> Gas Connection
                            </label>
                        </div>
                        <div class="checkbox-group checkbox-group-row">
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Cable TV" <?= checkboxesStates($utilities_included_update , $utilities_included , 'Cable TV') ?> disabled> Cable TV
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Solar Power" <?= checkboxesStates($utilities_included_update , $utilities_included , 'Solar Power') ?> disabled> Solar Power
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Backup Generator" <?= checkboxesStates($utilities_included_update , $utilities_included , 'Backup Generator') ?> disabled> Backup Generator
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="utilities_included[]" value="Waste Disposal" <?= checkboxesStates($utilities_included_update , $utilities_included , 'Waste Disposal') ?> disabled> Waste Disposal
                            </label>
                        </div>
                    </div>
                </div>



                <div class="input-group-group">
                    <label class="input-label">Additional Utilites*</label>
                    <textarea name="additional_utilities" class="input-field <?= check_equality($property, $propertyUpdate, 'additional_utilities') ? '' : 'not-equal' ?>" disabled><?= $propertyUpdate->additional_utilities ?></textarea>
                </div>


                <h3 class="form-headers">Security Details</h3>
                <?php
                // Assuming the $property->security_features is a comma-separated string from the database
                $security_features = isset($propertyUpdate->security_features) ? explode(',', $propertyUpdate->security_features) : [];
                $security_features_update = isset($property->security_features) ? explode(',', $property->security_features) : [];
                ?>

                <div class="input-group-group">
                    <div class="checkbox-group checkbox-group-column extra-margin-botton">
                        <div class="checkbox-group checkbox-group-row">
                            <label class="inline-label">
                                <input type="checkbox" name="security_features[]" value="CCTV" <?= checkboxesStates($security_features_update , $security_features , 'CCTV') ?> disabled> CCTV
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="security_features[]" value="Security Guards" <?= checkboxesStates($security_features_update , $security_features , 'Security Guards') ?> disabled> Security Guards
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="security_features[]" value="Intercom System" <?= checkboxesStates($security_features_update , $security_features , 'Intercom System') ?> disabled> Intercom System
                            </label>
                        </div>
                        <div class="checkbox-group checkbox-group-row">
                            <label class="inline-label">
                                <input type="checkbox" name="security_features[]" value="Access Control" <?= checkboxesStates($security_features_update , $security_features , 'Access Control') ?> disabled> Access Control
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="security_features[]" value="Fire Alarm" <?= checkboxesStates($security_features_update , $security_features , 'Fire Alarm') ?> disabled> Fire Alarm
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="security_features[]" value="Gated Community" <?= checkboxesStates($security_features_update , $security_features , 'Gated Community') ?> disabled> Gated Community
                            </label>
                        </div>
                    </div>
                </div>




                <h3 class="form-headers">Additional Amenities*</h3>
                <?php
                // Assuming $property->additional_amenities contains a comma-separated string
                $additional_amenities = isset($propertyUpdate->additional_amenities) ? explode(',', $propertyUpdate->additional_amenities) : [];
                $additional_amenities_update = isset($property->additional_amenities) ? explode(',', $property->additional_amenities) : [];
                ?>

                <div class="input-group-group">
                    <div class="checkbox-group checkbox-group-column extra-margin-botton">
                        <div class="checkbox-group checkbox-group-row">
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Swimming Pool" <?= checkboxesStates($additional_amenities_update , $additional_amenities , 'Swimming Pool') ?> disabled> Swimming Pool
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Gym" <?= checkboxesStates($additional_amenities_update , $additional_amenities , 'Gym') ?> disabled> Gym
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Garden" <?= checkboxesStates($additional_amenities_update , $additional_amenities , 'Garden') ?> disabled> Garden
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Elevator" <?= checkboxesStates($additional_amenities_update , $additional_amenities , 'Elevator') ?> disabled> Elevator
                            </label>
                        </div>
                        <div class="checkbox-group checkbox-group-row">
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Play Area" <?= checkboxesStates($additional_amenities_update , $additional_amenities , 'Play Area') ?> disabled> Play Area
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Clubhouse" <?= checkboxesStates($additional_amenities_update , $additional_amenities , 'Clubhouse') ?> disabled> Clubhouse
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="Jogging Track" <?= checkboxesStates($additional_amenities_update , $additional_amenities , 'Jogging Track') ?> disabled> Jogging Track
                            </label>
                            <label class="inline-label">
                                <input type="checkbox" name="additional_amenities[]" value="BBQ Area" <?= checkboxesStates($additional_amenities_update , $additional_amenities , 'BBQ Area') ?> disabled> BBQ Area
                            </label>
                        </div>
                    </div>
                </div>


                <h3 class="form-headers">Special Instructions*</h3>
                <?php
                // Assuming $property->special_instructions contains a comma-separated string
                $special_instructions = isset($propertyUpdate->special_instructions) ? explode(',', $propertyUpdate->special_instructions) : [];
                $special_instructions_update = isset($property->special_instructions) ? explode(',', $property->special_instructions) : [];
                ?>

                <div class="input-group">
                    <div class="checkbox-group checkbox-group-column">
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="No_Pets_Allowed" <?= checkboxesStates($special_instructions_update , $special_instructions , 'No_Pets_Allowed') ?> disabled> No Pets Allowed
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="No_Smoking_Inside_the_Property" <?= checkboxesStates($special_instructions_update , $special_instructions , 'No_Smoking_Inside_the_Property') ?> disabled> No Smoking Inside the Property
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Suitable_for_Families_Only" <?= checkboxesStates($special_instructions_update , $special_instructions , 'Suitable_for_Families_Only') ?> disabled> Suitable for Families Only
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="No_Loud_Music_or_Parties" <?= checkboxesStates($special_instructions_update , $special_instructions , 'No_Loud_Music_or_Parties') ?> disabled> No Loud Music or Parties
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Maintenance_Fee_Included" <?= checkboxesStates($special_instructions_update , $special_instructions , 'Maintenance_Fee_Included') ?> disabled> Maintenance Fee Included
                        </label>
                    </div>
                    <div class="checkbox-group checkbox-group-column">
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Tenant_Responsible_for_Utilities" <?= checkboxesStates($special_instructions_update , $special_instructions , 'Tenant_Responsible_for_Utilities') ?> disabled> Tenant Responsible for Utilities
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Lease_Renewal_Option_Available" <?= checkboxesStates($special_instructions_update , $special_instructions , 'Lease_Renewal_Option_Available') ?> disabled> Lease Renewal Option Available
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Immediate_Move_In_Available" <?= checkboxesStates($special_instructions_update , $special_instructions , 'Immediate_Move_In_Available') ?> disabled> Immediate Move-In Available
                        </label>
                        <label class="inline-label">
                            <input type="checkbox" name="special_instructions[]" value="Background_Check_Required_for_Tenants" <?= checkboxesStates($special_instructions_update , $special_instructions , 'Background_Check_Required_for_Tenants') ?> disabled> Background Check Required for Tenants
                        </label>
                    </div>
                </div>

            </div>



            <h3 class="form-headers">Owner Information*</h3>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Owner Name*</label>
                    <input type="text" name="owner_name" placeholder="Enter the Owner Name" value="<?= $propertyUpdate->owner_name ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'owner_name') ? '' : 'not-equal' ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Owner Email*</label>
                    <input type="text" name="owner_email" placeholder="Enter the Owner Email" value="<?= $propertyUpdate->owner_email ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'owner_email') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Owner Contact Number*</label>
                    <input type="text" name="owner_phone" placeholder="Enter the Owner Contact Number" value="<?= $propertyUpdate->owner_phone ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'owner_phone') ? '' : 'not-equal' ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Owner Additional Contact*</label>
                    <input type="text" name="additional_contact" placeholder="Enter a Additional Contact" value="<?= $propertyUpdate->additional_contact ?>" class="input-field <?= check_equality($property, $propertyUpdate, 'additional_contact') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>



            <h3 class="form-headers">Legal Details*</h3>
            <?php
            // Assuming $property->legal_details contains a comma-separated string
            $legal_details = isset($propertyUpdate->legal_details) ? explode(',', $propertyUpdate->legal_details) : [];
            $legal_details_update = isset($property->legal_details) ? explode(',', $property->legal_details) : [];
            ?>

            <div class="input-group">
                <div class="checkbox-group checkbox-group-column">
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Property_Ownership_Verified" <?= checkboxesStates($legal_details_update , $legal_details , 'Property_Ownership_Verified') ?> disabled> Property Ownership Verified
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Property_Free_from_Legal_Disputes" <?= checkboxesStates($legal_details_update , $legal_details , 'Property_Free_from_Legal_Disputes') ?> disabled> Property Free from Legal Disputes
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="All_Taxes_Paid_Up_to_Date" <?= checkboxesStates($legal_details_update , $legal_details , 'All_Taxes_Paid_Up_to_Date') ?> disabled> All Taxes Paid Up to Date
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Rental_Agreement_Draft_Available" <?= checkboxesStates($legal_details_update , $legal_details , 'Rental_Agreement_Draft_Available') ?> disabled> Rental Agreement Draft Available
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Property_Insured" <?= checkboxesStates($legal_details_update , $legal_details , 'Property_Insured') ?> disabled> Property Insured
                    </label>
                </div>
                <div class="checkbox-group checkbox-group-column">
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Compliance_with_Local_Housing_Laws" <?= checkboxesStates($legal_details_update , $legal_details , 'Compliance_with_Local_Housing_Laws') ?> disabled> Compliance with Local Housing Laws
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Tenant_Screening_Required" <?= checkboxesStates($legal_details_update , $legal_details , 'Tenant_Screening_Required') ?> disabled> Tenant Screening Required
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Lease_Agreement_Must_Be_Signed" <?= checkboxesStates($legal_details_update , $legal_details , 'Lease_Agreement_Must_Be_Signed') ?> disabled> Lease Agreement Must Be Signed
                    </label>
                    <label class="inline-label">
                        <input type="checkbox" name="legal_details[]" value="Security_Deposit_Refundable" <?= checkboxesStates($legal_details_update , $legal_details , 'Security_Deposit_Refundable') ?> disabled> Security Deposit Refundable (Subject to Conditions)
                    </label>
                </div>
            </div>

        </div>
    </div>
</form>

<div class="CP__button_container">
    <div class="CP__buttons">
        <button class="primary-btn" >Accept Update</button>
        <button class="primary-btn red">Reject Update</button>
    </div>
</div>


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

    function toggleRentFieldUpdated() {
        const typeSelect = document.getElementById('property-type-updated');
        const rentBasisField = document.getElementById('rent-basis-field-updated');

        if (typeSelect.value === 'monthly') {
            rentBasisField.style.display = 'block';
        } else {
            rentBasisField.style.display = 'none';
        }
    }

    // Initialize display based on current selection
    document.addEventListener('DOMContentLoaded', toggleRentFieldUpdated);
    document.addEventListener('DOMContentLoaded', toggleRentField);
</script>

<script>
    function handlePurposeChangeUpdated() {
        const purpose = document.getElementById('purpose-updated').value;
        const rentFields = document.getElementById('rent-fields-updated');
        const otherFields = document.getElementById('other-fields-updated');
        const restOfFeilds = document.getElementById('rest-of-fields-updated');

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

    function calculateRentalUpdated() {
        const durationInput = document.getElementById('safeguard_duration-updated');
        const baseRateInput = document.getElementById('safeguard_base_rate-updated');
        const totalDisplay = document.getElementById('total_display-updated');

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
    document.addEventListener('DOMContentLoaded', calculateRentalUpdated);
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

    function updateEndDateLimitUpdated() {
        const startInput = document.getElementById('start_date-updated');
        const endInput = document.getElementById('end_date-updated');

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

    function calculateRentalFromDatesUpdated() {
        const startInput = document.getElementById('start_date-updated');
        const endInput = document.getElementById('end_date-updated');
        const baseRate = parseFloat(document.getElementById('safeguard_base_rate-updated').value) || 0;
        const totalDisplay = document.getElementById('total_display-updated');

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



<?php require_once 'managerFooter.view.php'; ?>