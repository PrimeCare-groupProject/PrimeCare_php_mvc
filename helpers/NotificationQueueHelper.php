<?php

class NotificationQueueHelper {
    public static function buildQueue($notifications) {
        $queue = [];
        foreach ($notifications as $n) {
            enqueue(['notification_id' => $n->notification_id], $queue);
        }
        $queue = array_reverse($queue);
        while (count($queue) > 10) {
            dequeue($queue);
        }
        return $queue;
    }
}