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
        'pid'// foreign key
    ];

    public $errors = [];

    public function validate($data) {
        if (empty($data['name_on_card']) || !preg_match('/^[a-zA-Z]+$/', $data['name_on_card'])) {
            $this->errors['fname'] = 'Name on card not valid.';
        }
        if (!empty($data['card_no']) && !filter_var($data['card_no'], FILTER_VALIDATE_INT)) {
            $this->errors['card_no'] = 'Card Number must be consist only integers';
        }
        if (!empty($data['bank']) && !filter_var($data['bank'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 10]])) {
            $this->errors['bank'] = 'Invalid bank';
        }
        if (!empty($data['branch']) && !filter_var($data['branch'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 20]])) {
            $this->errors['branch'] = 'Invalid branch';
        }
    }
}
