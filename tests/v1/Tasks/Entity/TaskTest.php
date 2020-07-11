<?php

namespace Test\Docler\v1\Task\Entity;

use DateTimeImmutable;
use Docler\v1\Task\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testCreateObject(): void
    {
        $now = new DateTimeImmutable();

        $task = new Task(
            1,
            'Title',
            'Description',
            999,
            $now
        );

        $this->assertEquals(1, $task->id);
        $this->assertEquals('Title', $task->title);
        $this->assertEquals('Description', $task->description);
        $this->assertEquals(999, $task->priority);
        $this->assertEquals($now, $task->created);
    }

    public function testJsonAble(): void
    {
        $now = new DateTimeImmutable();

        $task = new Task(
            1,
            'Title',
            'Description',
            999,
            $now
        );

        $task = json_encode($task);

        $jsonString = '{"id":1,"title":"Title","description":"Description","priority":999,"created":{"date":"' . $now->format('Y-m-d H:i:s.u') . '","timezone_type":3,"timezone":"UTC"},"modified":null,"removed":null}';

        $this->assertJson($task);
        $this->assertEquals(json_decode($task, true), json_decode($jsonString, true));
        $this->assertJsonStringEqualsJsonString($jsonString, $task);
    }
}
