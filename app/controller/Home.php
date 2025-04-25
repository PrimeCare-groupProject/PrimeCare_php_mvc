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
        $properties = $property->where(['status' => 'Active' , 'purpose' => 'Rent']);
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
                    $_SESSION['flash'] = [
                        'msg' => 'Could not create user. Please try again later.',
                        'type' => 'error'
                    ];
                    
                    $this->view('hometest', ['properties' => $cardDetails['properties'], 'services' => $cardDetails['services']]);
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
                    $_SESSION['flash'] = [
                        'msg' => 'Message sent successfully! We will contact you shortly.',
                        'type' => 'success'
                    ];
                    
                    // Send email to the user with improved styling
                    $status = sendMail($personData['email'], 'Thank you for contacting PrimeCare', "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px;'>
                            <div style='text-align: center; padding: 10px 0; background-color: #f8f9fa;'>
                                <h2 style='color: #0056b3;'>PrimeCare Support</h2>
                            </div>
                            <div style='padding: 20px 0;'>
                                <p>Hello {$personData['name']},</p>
                                <p>Thank you for reaching out to us. We have received your message:</p>
                                <div style='background-color: #f8f9fa; padding: 15px; border-left: 4px solid #0056b3; margin: 15px 0;'>
                                    <em>\"{$messageData['message']}\"</em>
                                </div>
                                <p>Our team will review your inquiry and get back to you as soon as possible.</p>
                                <p>If you have any urgent matters, please contact us directly at <a href='tel:+94762213874'>+94762213874</a>.</p>
                            </div>
                            <div style='text-align: center; padding: 10px 0; font-size: 12px; color: #6c757d; border-top: 1px solid #eee;'>
                                <p>© PrimeCare Support Team</p>
                            </div>
                        </div>
                    ");
                    
                    if ($status['error']) {
                        $_SESSION['flash'] = [
                            'msg' => 'Your message was received, but we encountered an issue sending you a confirmation email.',
                            'type' => 'warning'
                        ];
                    }
                    
                    $this->view('hometest', ['properties' => $cardDetails['properties'], 'services' => $cardDetails['services']]);
                    return;
                } else {
                    $_SESSION['flash'] = [
                        'msg' => 'Failed to send your message. Please try again later.',
                        'type' => 'error'
                    ];
                    $this->view('hometest', ['properties' => $cardDetails['properties'], 'services' => $cardDetails['services']]);
                    return;
                }
            } else {
                $_SESSION['flash'] = [
                    'msg' => 'Please enter a message before submitting.',
                    'type' => 'error'
                ];
                $this->view('hometest', ['properties' => $cardDetails['properties'], 'services' => $cardDetails['services']]);
                return;
            }
        } else {
            // Validation failed, display errors using flash
            $_SESSION['flash'] = [
                'msg' => 'There were validation errors with your submission.',
                'type' => 'error',
                'errors' => $randomPerson->errors
            ];
            $this->view('hometest', ['properties' => $cardDetails['properties'], 'services' => $cardDetails['services']]);
            return;
        }
    }
}
