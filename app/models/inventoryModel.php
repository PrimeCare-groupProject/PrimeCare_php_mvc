<?php
// Property class
class InventoryModel
{
    use Model;

    protected $table = 'inventory';
    protected $order_column = "inventory_id";
    protected $allowedColumns = [
        'inventory_name',
        'date',
        'unit_price',
        'quantity',
        'total_price',
        'description',
        'seller_name',
        'seller_address',
        'img_url',
        'property_id',
        'property_name',
        'inventory_type'
    ];

    public $errors = [];
    public $success = [];

    public function validateInventory($data)
    {
        $this->errors = [];

        // Validate inventory_name
        if (empty($data['inventory_name']) || !preg_match('/^[a-zA-Z0-9\s\-]+$/', $data['inventory_name'])) {
            $this->errors['inventory_name'] = 'Inventory name is required and can only contain letters, numbers, spaces, and hyphens.';
        }

        // Validate date (assuming format YYYY-MM-DD)
        if (empty($data['date']) || !strtotime($data['date'])) {
            $this->errors['date'] = 'Valid date is required.';
        }

        // Validate unit_price
        if (!isset($data['unit_price']) || !is_numeric($data['unit_price']) || $data['unit_price'] < 0) {
            $this->errors['unit_price'] = 'Unit price must be a positive number.';
        }

        // Validate quantity
        if (!isset($data['quantity']) || !filter_var($data['quantity'], FILTER_VALIDATE_INT) || $data['quantity'] < 0) {
            $this->errors['quantity'] = 'Quantity must be a positive whole number.';
        }

        // Validate total_price (auto-calculated, but still should be validated if provided)
        if (isset($data['total_price']) && (!is_numeric($data['total_price']) || $data['total_price'] < 0)) {
            $this->errors['total_price'] = 'Total price must be a positive number.';
        }

        // Validate description (optional)
        if (!empty($data['description']) && strlen($data['description']) > 1000) {
            $this->errors['description'] = 'Description cannot exceed 1000 characters.';
        }

        // Validate seller_name
        if (empty($data['seller_name']) || !preg_match('/^[a-zA-Z\s\-\.]+$/', $data['seller_name'])) {
            $this->errors['seller_name'] = 'Valid seller name is required.';
        }

        // Validate seller_address
        if (empty($data['seller_address']) || strlen(trim($data['seller_address'])) < 5) {
            $this->errors['seller_address'] = 'Seller address must be at least 5 characters.';
        }

        // Validate img_url (optional)
        if (!empty($data['img_url']) && !filter_var($data['img_url'], FILTER_VALIDATE_URL)) {
            $this->errors['img_url'] = 'Invalid image URL format.';
        }

        // Validate property_id (if required)
        if (!empty($data['property_id']) && !filter_var($data['property_id'], FILTER_VALIDATE_INT)) {
            $this->errors['property_id'] = 'Invalid property ID.';
        }

        // Validate property_name (optional)
        if (!empty($data['property_name']) && !preg_match('/^[a-zA-Z0-9\s\-]+$/', $data['property_name'])) {
            $this->errors['property_name'] = 'Property name can only contain letters, numbers, spaces, and hyphens.';
        }

        // Validate inventory_type
        $validTypes = ['Furniture', 'Equipment', 'Supplies', 'Other']; // Add your valid types
        if (empty($data['inventory_type']) || !in_array($data['inventory_type'], $validTypes)) {
            $this->errors['inventory_type'] = 'Please select a valid inventory type.';
        }

        // Return true if no errors, otherwise false
        return empty($this->errors);
    }
}