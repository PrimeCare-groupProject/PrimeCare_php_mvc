<?php
defined('ROOTPATH') or exit('Access denied');

class Notification
{

    use controller;

    public function index()
    {
        echo "Notification index";
    }

    public function create($user, $title, $message)
    {
        $notificationModel = new NotificationModel();
        $data = [
            'user_id' => $user,
            'title' => $title,
            'message' => $message,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        if ($notificationModel->insert($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getNotifications($user_id)
    {
        $notificationModel = new NotificationModel();
        $notifications = $notificationModel->where(['user_id' => $user_id]);
        return $notifications;
    }

    public function markAsRead($notification_id)
    {
        $notificationModel = new NotificationModel();
        $data = [
            'is_read' => 1
        ];
        if ($notificationModel->update($data, $notification_id)) {
            return true;
        } else {
            return false;
        }
    }

    public function readAll()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['user'])) {
            $notificationModel = new NotificationModel();
            $notificationModel->setReadNotification($_SESSION['user']->pid);
            echo json_encode(['status' => 'success']);
        }
    }
}
