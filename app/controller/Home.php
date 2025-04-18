<?php
defined('ROOTPATH') or exit('Access denied');

// Use controller trait
class Home
{
    use controller;

    private function getCardDetils(){
        $property = new PropertyConcat;
        $services = new Services;

        // Fetch services and pending properties
        $services = $services->findAll();
        $properties = $property->where(['status' => 'pending']);
        return ['properties' => $properties, 'services' => $services];
    }

    public function index()
    {
        // Handle contact form submission
        if (isset($_POST['action']) && $_POST['action'] === 'contactus') {
            $this->contactUs();
            return;
        }
        
        $cardDetails = $this->getCardDetils();
        

        // Render view
        $this->view('hometest', ['properties' => $cardDetails['properties'], 'services' => $cardDetails['services']]);
    }

    public function serviceListing()
    {
        $this->view('serviceListing');
    }

    private function contactUs()
    {
        //getting card details
        $cardDetails = $this->getCardDetils();
        
        $randomPerson = new RandomPerson;
        $randomMessage = new RandomMessage;

        // Collecting data from POST request
        $personData = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'contactNo' => $_POST['phone'] ?? ''
        ];

        $successMessage = '';
        $errorMessage = '';

        // Validate data
        $isValid = $randomPerson->validate($personData);

        if ($isValid) {
            // Check if the person already exists
            $user = $randomPerson->first(['email' => $personData['email']], []);
            if (!$user || !isset($user->email)) {
                // User does not exist; insert new user
                $respond = $randomPerson->insert($personData);
                if ($respond) {
                    $user = $randomPerson->first(['email' => $personData['email']], []);
                } else {
                    $randomPerson->errors['general'] = 'Could not create user. Please try again later.';
                    
                    $this->view('hometest', ['errors' => $randomPerson->errors, 'properties' => $cardDetails['properties'], 'services' => $cardDetails['services']]);
                    return;
                }
            }

            // Add the message associated with the user
            $messageData = [
                'message' => $_POST['message'] ?? '',
                'pid' => $user->pid
            ];

            if (!empty($messageData['message'])) {
                $messageRespond = $randomMessage->insert($messageData);
                if ($messageRespond) {
                    $successMessage = 'Message sent successfully! We will contact you shortly.';
                    $this->view('hometest', ['success' => $successMessage, 'properties' => $cardDetails['properties'], 'services' => $cardDetails['services']]);
                    return;
                } else {
                    $randomPerson->errors['message'] = 'Failed to send your message. Please try again.';
                    $this->view('hometest', ['errors' => $randomPerson->errors, 'properties' => $cardDetails['properties'], 'services' => $cardDetails['services']]);
                    return;
                }
            } else {
                $randomPerson->errors['message'] = 'Message field cannot be empty.';
                $this->view('hometest', ['errors' => $randomPerson->errors, 'properties' => $cardDetails['properties'], 'services' => $cardDetails['services']]);
                return;
            }
        } else {
            // Validation failed, display errors
            $this->view('hometest', ['errors' => $randomPerson->errors, 'properties' => $cardDetails['properties'], 'services' => $cardDetails['services']]);
            return;
        }
    }
}
