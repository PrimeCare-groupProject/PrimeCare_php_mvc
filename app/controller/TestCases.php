<?php
defined('ROOTPATH') or exit('Access denied');

class TestCases
{
    use controller;

    public function index()
    {
        echo "Hello from TestCases controller!";
    }

    public function createProperty()
    {
        $property = new Property;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
            $json = file_get_contents("php://input");
            $decoded = json_decode($json, true);

            if (is_array($decoded)) {
                $_POST = array_merge($_POST, $decoded);
            }

            if (!$property->validateProperty($_POST)) {
                echo "Validation failed:\n";
                print_r($property->errors);
                echo "Redirecting to the property adding page...";
                return;
            }

            $purpose = $_POST['purpose'] ?? 'Rent';
            $rental_period = $_POST['rental_period'] ?? 'Daily';
            $start_date = $_POST['start_date'] ?? date('Y-m-d');
            $end_date = $_POST['end_date'] ?? date('Y-m-d', strtotime('+7 days', strtotime($start_date)));

            if ($purpose == 'Rent') {
                $rental_price = $_POST['rental_price'] ?? 0;
            } else {
                $start_timestamp = strtotime($start_date);
                $end_timestamp = strtotime($end_date);

                if ($start_timestamp && $end_timestamp) {
                    $duration_in_days = ($end_timestamp - $start_timestamp) / (60 * 60 * 24);

                    if ($duration_in_days <= 0) {
                        $duration_in_days = 1;
                    }
                    $rental_price = $duration_in_days * RENTAL_PRICE;
                } else {
                    $rental_price = 0;
                }
            }

            $arr = [
                'name' => $_POST['name'],
                'type' => $_POST['type'] ?? 'House',
                'description' => $_POST['description'] ?? '',

                'address' => $_POST['address'] ?? '',
                'zipcode' => $_POST['zipcode'] ?? '',
                'city' => $_POST['city'] ?? '',
                'state_province' => $_POST['state_province'] ?? '',
                'country' => $_POST['country'] ?? 'Sri Lanka',

                'year_built' => $_POST['year_built'] ?? 2025,
                'size_sqr_ft' => $_POST['size_sqr_ft'] ?? 0,
                'number_of_floors' => $_POST['number_of_floors'] ?? 0,
                'floor_plan' => $_POST['floor_plan'] ?? 'no',

                'units' => $_POST['units'] ?? 0,
                'bedrooms' => $_POST['bedrooms'] ?? 0,
                'bathrooms' => $_POST['bathrooms'] ?? 0,
                'kitchen' => $_POST['kitchen'] ?? 'no',
                'living_room' => $_POST['living_room'] ?? 'no',

                'furnished' => $_POST['furnished'] ?? 'no',
                'furniture_description' => $_POST['furniture_description'] ?? '',

                'parking' => $_POST['parking'] ?? 'no',
                'parking_slots' => $_POST['parking_slots'] ?? 0,
                'type_of_parking' => $_POST['type_of_parking'] ?? 'none',

                'utilities_included' => isset($_POST['utilities_included']) && is_array($_POST['utilities_included']) ? implode(',', $_POST['utilities_included']) : ($_POST['utilities_included'] ?? ''),
                'additional_utilities' => isset($_POST['additional_utilities']) && is_array($_POST['additional_utilities']) ? implode(',', $_POST['additional_utilities']) : ($_POST['additional_utilities'] ?? ''),
                'additional_amenities' => isset($_POST['additional_amenities']) && is_array($_POST['additional_amenities']) ? implode(',', $_POST['additional_amenities']) : ($_POST['additional_amenities'] ?? ''),
                'security_features' => isset($_POST['security_features']) && is_array($_POST['security_features']) ? implode(',', $_POST['security_features']) : ($_POST['security_features'] ?? ''),

                'purpose' => $purpose,
                'rental_period' => $rental_period,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'rental_price' => $rental_price,

                'owner_name' => $_POST['owner_name'],
                'owner_email' => $_POST['owner_email'],
                'owner_phone' => $_POST['owner_phone'] ?? '',
                'additional_contact' => $_POST['additional_contact'],

                'special_instructions' => isset($_POST['special_instructions']) && is_array($_POST['special_instructions']) ? implode(',', $_POST['special_instructions']) : ($_POST['special_instructions'] ?? ''),
                'legal_details' => isset($_POST['legal_details']) && is_array($_POST['legal_details']) ? implode(',', $_POST['legal_details']) : ($_POST['legal_details'] ?? ''),

                'status' => 'pending',
                'person_id' => 122,
                'agent_id' => 110,
                'duration' => $_POST['duration'] ?? 1
            ];

            $res = $_POST['res'] ?? false;

            if ($res) {
                echo "Property added successfully! and redirect to the property listing page.";
            } else {
                echo "Failed to add property. And redirect to the propoerty Listing page.";
            }
        } else {
            echo "No POST request detected. And redirect to the property Adding page.";
        }
    }

    public function deleteRequest($propertyStatus, $propertyIsThere, $successFullDeletion)
    {
        if ($propertyStatus == 'Pending') {

            if ($propertyIsThere) {
                $msg = "Property deleted successfully!";
            } else {
                $msg = "Failed to delete property. Please try again.";
            }

            echo $msg;
            echo "\n";
            echo "redirect('property/propertyListing')";
        } elseif ($propertyStatus == 'Occupied') {
            $msg = "You cannot delete an occupied property!";

            echo $msg;
            echo "\n";
            echo "redirect('property/propertyListing')";
        } elseif ($propertyStatus == 'Active') {

            if ($successFullDeletion) {
                $msg = "Property deletion request sent successfully!";
            } else {
                $msg = "Failed to send property deletion request. Please try again.";
            }
            echo $msg;
            echo "\n";
            echo "redirect('property/propertyListing')";
        }
    }

    public function confirmDeletion($isApproved, $isUpdated)
    {
        if ($isApproved == "Not_Approve") {
            $msg = "Failed to approve property request. Please try again.";
            echo $msg;
            echo "\n";
            echo "redirect('property/propertyListing')";
            return;
        }

        if ($isUpdated == "Cannot_Inactive") {
            echo "Failed to approve property request. Please try again.";
            echo "\n";
            echo "redirect('property/propertyListing')";
            return;
        }

        echo "Property Removal request approved successfully!";
        echo "\n";
        echo "redirect('dashboard/property/removalRequests')";
    }

    public function rejectDeletion($isSuccess)
    {
        if ($isSuccess == "Failed") {
            echo "Failed to decline property request. Please try again.";
            echo "\n";
            echo "redirect('dashboard/property/removalRequests')";
            return;
        }
        echo "Property Removal request declined successfully!";
        echo "\n";
        echo "redirect('dashboard/property/removalRequests')";
    }

    public function confirmAssign($isUpdate)
    {
        if ($isUpdate == "Success") {
            echo "Property assigned to agent successfully!";
            echo "\n";
        } else {
            echo "Failed to assign property to agent. Please try again.";
            echo "\n";
        }
        echo "redirect('dashboard/managementhome/propertymanagement/assignagents')";
    }

    public function submitPreInspection($propertyID)
    {
        $json = file_get_contents("php://input");
        $decoded = json_decode($json, true);
        if (is_array($decoded)) {
            $_POST = array_merge($_POST, $decoded);
        }

        $preInspection = new PreInspection;

        if ($propertyID) {
            $data = [
                'agent_id' => $_POST['agent_id'],
                'property_id' => $propertyID,
                'provided_details' => $_POST['provided_details'],
                'title_deed' => $_POST['title_deed'],
                'utility_bills' => $_POST['utility_bills'],
                'owner_id_copy' => $_POST['owner_id_copy'],
                'lease_agreement' => $_POST['lease_agreement'],
                'property_condition' => $_POST['property_condition'],
                'Maintenance_issues' => $_POST['Maintenance_issues'],
                'owner_present' => $_POST['owner_present'],
                'notes' => $_POST['notes'],
                'recommendation' => $_POST['recommendation']
            ];
            $res = $_POST['res'] ?? false;
            if ($res) {
                if ($preInspection->isValidForRegister($data)) {
                    $message_to_owner = $preInspection->messages['recommendation'] ?? null;
                    if (!$message_to_owner) {
                        $message_to_owner = 'And No recommendation provided.';
                    }
                    echo "Pre-Inspection submitted successfully!";
                    echo "\n";
                    echo "Recommendation: " . $message_to_owner;
                } else {
                    $message = $preInspection->getValidationMessages();
                    echo "Pre-Inspection submission failed:\n";
                    echo $message;
                    echo "\n";
                }
            } else {
                echo "Failed to submit Pre-Inspection. Please try again.";
                echo "\n";
            }
        } else {
            echo "Property not found to submit Pre-Inspection.";
            echo "\n";
        }
        echo "Redirecting to the Pre-Inspection page...";
    }
    
}
