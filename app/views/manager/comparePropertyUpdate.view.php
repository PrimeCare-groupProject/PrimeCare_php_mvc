<?php require_once 'managerHeader.view.php'; ?>

<?php
    function check_equality($property, $propertyUpdate, $field){
        if($property->$field == $propertyUpdate->$field){
            return true;
        }else{
            return false;
        }
    }
?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Compare Details on <span style="color: var(--green-color);"><?= $property->name ?></span></h2>
</div>

<form>
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">Name Of Property</label>
            <input type="text" value="<?= $property->name ?>" class="input-field" disabled>


            <label class="input-label">Type</label>
            <input name="type" class="input-field" id="property-type" value="<?= $property->type ?>" disabled>


            <label class="input-label">Description</label>
            <textarea name="description" placeholder="Write About Property" class="input-field" disabled style="height: 150px;"><?= $property->description ?></textarea>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Year Built</label>
                    <input type="text" value="<?= $property->year_built ?>" class="input-field" disabled>
                </div>
                <div class="input-group-group" id="rent-basis-field">
                    <label class="input-label" disabled>Monthly Rent In LKR</label>
                    <input type="number" value="<?= $property->rent_on_basis ?>" class="input-field" value="0">
                </div>
            </div>


            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Furnished</label>
                    <input name="furnished" class="input-field" value="<?= $property->furnished ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Parking</label>
                    <input name="parking" class="input-field" value="<?= $property->parking ?>" disabled>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Country</label>
                    <input type="text" value="<?= $property->country ?>" class="input-field" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">State</label>
                    <input type="text" value="<?= $property->state_province ?>" class="input-field" disabled>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">City</label>
                    <input type="text" value="<?= $property->city ?>" class="input-field" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Zip Code</label>
                    <input type="text" value="<?= $property->zipcode ?>" class="input-field" disabled>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Address</label>
                    <input type="text" value="<?= $property->address ?>" class="input-field" disabled>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">No Of Units Of Property</label>
                    <input type="text" value="<?= $property->units ?>" class="input-field" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Size Of Property In Square Roots</label>
                    <input type="text" value="<?= $property->size_sqr_ft ?>" class="input-field" disabled>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Bedrooms</label>
                    <input type="text" value="<?= $property->bedrooms ?>" class="input-field" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Bathrooms</label>
                    <input type="text" value="<?= $property->bathrooms ?>" class="input-field" disabled>
                </div>
            </div>

            <div class="input-group-group">
                <label class="input-label">Floor Plan</label>
                <textarea class="input-field" disabled style="height: 150px;"><?= $property->floor_plan ?></textarea>
            </div>

        </div>


        <div class="owner-addProp-form-right">
            <label class="input-label">Name Of Property</label>
            <input type="text" value="<?= $propertyUpdate->name ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'name') ? '' : 'not-equal' ?>" disabled>


            <label class="input-label">Type</label>
            <input name="type" class="input-field <?= check_equality($property , $propertyUpdate , 'type') ? '' : 'not-equal' ?>" id="property-type" value="<?= $propertyUpdate->type ?>" disabled>


            <label class="input-label">Description</label>
            <textarea name="description" placeholder="Write About Property" class="input-field <?= check_equality($property , $propertyUpdate , 'description') ? '' : 'not-equal' ?>" disabled style="height: 150px;"><?= $propertyUpdate->description ?></textarea>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Year Built</label>
                    <input type="text" value="<?= $propertyUpdate->year_built ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'year_built') ? '' : 'not-equal' ?>" disabled>
                </div>
                <div class="input-group-group" id="rent-basis-field">
                    <label class="input-label" disabled>Monthly Rent In LKR</label>
                    <input type="number" value="<?= $propertyUpdate->rent_on_basis ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'rent_on_basis') ? '' : 'not-equal' ?>" value="0">
                </div>
            </div>


            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Furnished</label>
                    <input name="furnished" class="input-field <?= check_equality($property , $propertyUpdate , 'furnished') ? '' : 'not-equal' ?>" value="<?= $propertyUpdate->furnished ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Parking</label>
                    <input name="parking" class="input-field <?= check_equality($property , $propertyUpdate , 'parking') ? '' : 'not-equal' ?>" value="<?= $propertyUpdate->parking ?>" disabled>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Country</label>
                    <input type="text" value="<?= $propertyUpdate->country ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'country') ? '' : 'not-equal' ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">State</label>
                    <input type="text" value="<?= $propertyUpdate->state_province ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'state_province') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">City</label>
                    <input type="text" value="<?= $propertyUpdate->city ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'city') ? '' : 'not-equal' ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Zip Code</label>
                    <input type="text" value="<?= $propertyUpdate->zipcode ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'zipcode') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Address</label>
                    <input type="text" value="<?= $propertyUpdate->address ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'address') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">No Of Units Of Property</label>
                    <input type="text" value="<?= $propertyUpdate->units ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'units') ? '' : 'not-equal' ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Size Of Property In Square Roots</label>
                    <input type="text" value="<?= $propertyUpdate->size_sqr_ft ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'size_sqr_ft') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Bedrooms</label>
                    <input type="text" value="<?= $propertyUpdate->bedrooms ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'bedrooms') ? '' : 'not-equal' ?>" disabled>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Bathrooms</label>
                    <input type="text" value="<?= $propertyUpdate->bathrooms ?>" class="input-field <?= check_equality($property , $propertyUpdate , 'bathrooms') ? '' : 'not-equal' ?>" disabled>
                </div>
            </div>

            <div class="input-group-group">
                <label class="input-label">Floor Plan</label>
                <textarea class="input-field <?= check_equality($property , $propertyUpdate , 'floor_plan') ? '' : 'not-equal' ?>" disabled style="height: 150px;"><?= $propertyUpdate->floor_plan ?></textarea>
            </div>
        </div>
    </div>
</form>

<div class="CP__button_container">
    <div class="CP__buttons">
        <button class="primary-btn">Accept Update</button>
        <button class="primary-btn red">Reject Update</button>
    </div>
</div>

<?php require_once 'managerFooter.view.php'; ?>