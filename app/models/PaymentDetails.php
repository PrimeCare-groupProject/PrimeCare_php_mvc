<?php
// Property class
class PaymentDetails
{
    use Model;

    protected $table = 'payment_details';
    protected $order_column = "card_no";
    protected $allowedColumns = [
        'card_name',
        'account_no',
        'bank',//10
        'branch',//20
        'pid'
    ];

    public $errors = [];

    private function validate($formData){
        $errors = [];

        // Email validation
        if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address.';
        }

        // Contact number validation (10-digit number)
        if (empty($formData['contact']) || !preg_match('/^[0-9]{10}$/', $formData['contact'])) {
            $errors['contact'] = 'Contact number must be exactly 10 digits.';
        }

        // Name validation
        if (empty($formData['fname']) || !preg_match('/^[a-zA-Z]+$/', $formData['fname'])) {
            $errors['fname'] = 'First name must contain only alphabets.';
        }
        if (empty($formData['lname']) || !preg_match('/^[a-zA-Z]+$/', $formData['lname'])) {
            $errors['lname'] = 'Last name must contain only alphabets.';
        }

        // Card name validation
        if (empty($formData['cardName']) || !preg_match('/^[a-zA-Z ]+$/', $formData['cardName'])) {
            $errors['cardName'] = 'Card name must contain only alphabets and spaces.';
        }

        // Account number validation (numeric and specific length)
        if (empty($formData['accountNo']) || !preg_match('/^\d{8,20}$/', $formData['accountNo'])) {
            $errors['accountNo'] = 'Account number must be numeric and between 8 to 20 digits.';
        }

        // Bank name validation
        if (empty($formData['bankName']) || strlen($formData['bankName']) > 50) {
            $errors['bankName'] = 'Bank name must be provided and not exceed 50 characters.';
        }

        // Branch validation (numeric, within range)
        if (!empty($formData['branch']) && (!filter_var($formData['branch'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 20]]))) {
            $errors['branch'] = 'Branch must be a number between 1 and 20.';
        }

        // Bank ID validation (numeric, within range)
        if (!empty($formData['bank']) && (!filter_var($formData['bank'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]]))) {
            $errors['bank'] = 'Bank must be a number between 1 and 10.';
        }

        return $errors;
    }
}
