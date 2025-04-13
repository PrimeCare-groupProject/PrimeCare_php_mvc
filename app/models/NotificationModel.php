<?php
// Property class
class NotificationModel
{
    use Model;

    protected $table = 'notifications';
    protected $order_column = "notification_id";
    protected $allowedColumns = [
        'notification_id',
        'user_id',
        'title',
        'message',
        'link',
        'color',
        'is_read',
        'created_at'
    ];

    public $errors = [];

}