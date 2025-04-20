<?php

class ProblemReportView {
    use Model;

    protected $table = 'view_report_problems_with_images';
    protected $order_column = "report_id";
    protected $allowedColumns = [
        'report_id',
        'problem_description',
        'urgency_level',
        'urgency_label',
        'property_id',
        'status',
        'submitted_at',
        'image_url'
    ];

    // Fetch all reports for a specific property_id
    public function getReports($property_id) {
        $query = "SELECT * FROM {$this->table} WHERE property_id = :property_id ORDER BY submitted_at DESC";
        return $this->instance->query($query, ['property_id' => $property_id]);
    }

    public function getReportsWithImages($property_id, $orderby = "date") {
        switch($orderby){
            case 'date':
                $query = "SELECT * FROM {$this->table} WHERE property_id = :property_id 
                    ORDER BY 
                    FIELD(status, 'pending', 'in_progress', 'resolved'),
                    urgency_level DESC";
                break;
            default:
                $query = "SELECT * FROM {$this->table} WHERE property_id = :property_id ORDER BY submitted_at DESC";
                break;
        }
        
        $rows = $this->instance->query($query, ['property_id' => $property_id]);
    
        $reports = [];
        if (is_array($rows) && !empty($rows)) {
            foreach ($rows as $row) {
                $report_id = $row->report_id;
                if (!isset($reports[$report_id])) {
                    // Initialize report details and images array
                    $reports[$report_id] = [
                    'report_id' => $row->report_id,
                    'problem_description' => $row->problem_description,
                    'urgency_level' => $row->urgency_level,
                    'urgency_label' => $row->urgency_label,
                    'property_id' => $row->property_id,
                    'status' => $row->status,
                    'submitted_at' => $row->submitted_at,
                    'images' => [],
                    ];
                }
                if (!empty($row->image_url)) {
                    $reports[$report_id]['images'][] = $row->image_url;
                }
            }
        }
        // Re-index array numerically
        return array_values($reports);
    }

}