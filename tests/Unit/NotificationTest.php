<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../app/core/functions.php';
require_once __DIR__ . '/../../helpers/NotificationQueueHelper.php';

class NotificationTest extends TestCase
{
    public function testQueueIsLimitedToTen() {
        $notifications = [];
        for ($i = 1; $i <= 15; $i++) {
            $n = (object)['notification_id' => $i];
            $notifications[] = $n;
        }

        $queue = NotificationQueueHelper::buildQueue($notifications);
        $this->assertCount(10, $queue);
    }

    public function testDequeue_ReturnsCorrectId(){
        $queue = [
            ['notification_id' => 101],
            ['notification_id' => 102],
            ['notification_id' => 103] 
        ];

        $id = dequeue($queue);
        $this->assertEquals(103 , $id);
        $this->assertCount(2 , $queue);
    }

    public function testEnqueue_AddsToFront(){
        $queue = [
            ['notification_id' => 101],
            ['notification_id' => 102]
        ];

        enqueue([
            'notification_id' => 103
        ], $queue);

        $this->assertEquals(103 , $queue[0]['notification_id']);
        $this->assertEquals(101 , $queue[1]['notification_id']);
    }

}

