<?php

class ProblemReportImage {
    use Model;

    protected $table = 'ProblemReportImages';
    protected $order_column = "image_id";
    protected $allowedColumns = [
        'image_id',
        'report_id',
        'image_url',
        'uploaded_at'
    ];

    public $errors = [];

    public function validate($data) {
        $this->errors = [];

        if(empty($data['report_id']) || !is_numeric($data['report_id'])) {
            $this->errors['report_id'] = "Valid report ID is required";
        }

        if(empty($data['image_url'])) {
            $this->errors['image_url'] = "Image URL is required";
        }

        return empty($this->errors);
    }

    public function getImagesByReport($report_id) {
        $query = "SELECT * FROM {$this->table} WHERE report_id = :report_id ORDER BY uploaded_at DESC";
        return $this->instance->query($query, ['report_id' => $report_id]);
    }

    public function deleteImagesForReport($report_id) {
        // First get all images to delete files
        $images = $this->getImagesByReport($report_id);
        
        // Delete actual image files
        foreach($images as $image) {
            if(file_exists($image->image_url)) {
                unlink($image->image_url);
            }
        }

        // Delete database records
        $query = "DELETE FROM {$this->table} WHERE report_id = :report_id";
        return $this->instance->query($query, ['report_id' => $report_id]);
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}