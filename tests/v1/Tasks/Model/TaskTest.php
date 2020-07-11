<?php

namespace Test\Docler\v1\Task\Model;

use DateTimeImmutable;
use Docler\v1\Task\Entity\Task as TaskEntities;
use Docler\v1\Task\Model\Task as TaskModel;
use Docler\v1\Task\Repository\Task as TaskMapper;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testSave(): void
    {
        $taskMapper = $this->createMock(TaskMapper::class);

        $taskMapper->expects($this->once())
            ->method('save')
            ->willReturn(1);

        $taskModel = new TaskModel($taskMapper);
        $id = $taskModel->save('Title', 'Description', 1);

        $this->assertIsInt($id);
        $this->assertEquals(1, $id);
    }

    public function testDelete(): void
    {
        $taskMapper = $this->createMock(TaskMapper::class);

        $taskMapper->expects($this->once())
            ->method('delete');

        $taskModel = new TaskModel($taskMapper);
        $void = $taskModel->delete(1);

        $this->assertNull($void);
    }

    public function testUpdate(): void
    {
        $taskMapper = $this->createMock(TaskMapper::class);

        $taskMapper->expects($this->once())
            ->method('update');

        $taskModel = new TaskModel($taskMapper);
        $void = $taskModel->update(1, 'Title', 'Description', 1);

        $this->assertNull($void);
    }

    public function testFind(): void
    {
        $taskMapper = $this->createMock(TaskMapper::class);

        $task = new TaskEntities(
            999,
            'Do some homework',
            'Lets write some cool unit tests',
            1,
            new DateTimeImmutable()
        );

        $taskMapper->expects($this->once())
            ->method('find')
            ->willReturn($task);

        $taskModel = new TaskModel($taskMapper);
        $task = $taskModel->find(1);

        $this->assertInstanceOf(
            TaskEntities::class,
            $task
        );
        $this->assertEquals(999, $task->id);
        $this->assertEquals('Do some homework', $task->title);
        $this->assertEquals('Lets write some cool unit tests', $task->description);
        $this->assertEquals(1, $task->priority);
    }

    public function testFindOnNull(): void
    {
        $taskMapper = $this->createMock(TaskMapper::class);

        $taskMapper->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $taskModel = new TaskModel($taskMapper);
        $void = $taskModel->find(1);

        $this->assertNull($void);
    }

    public function testAll(): void
    {
        $taskMapper = $this->createMock(TaskMapper::class);

        $task1 = new TaskEntities(
            1,
            'TODO 1',
            'Lets analyse our new project',
            1,
            new DateTimeImmutable()
        );
        $task2 = new TaskEntities(
            2,
            'TODO 2',
            'Lets write some cool unit tests',
            2,
            new DateTimeImmutable()
        );
        $task3 = new TaskEntities(
            3,
            'TODO 3',
            'Lets write some PHP code',
            3,
            new DateTimeImmutable()
        );


        $taskMapper->expects($this->once())
            ->method('all')
            ->willReturn([$task1, $task2, $task3]);

        $taskModel = new TaskModel($taskMapper);
        $allTasks = $taskModel->all();

        $this->assertIsArray($allTasks);

        $this->assertEquals(1, $allTasks[0]->id);
        $this->assertEquals('TODO 1', $allTasks[0]->title);
        $this->assertEquals('Lets analyse our new project', $allTasks[0]->description);
        $this->assertEquals(1, $allTasks[0]->priority);

        $this->assertEquals(2, $allTasks[1]->id);
        $this->assertEquals('TODO 2', $allTasks[1]->title);
        $this->assertEquals('Lets write some cool unit tests', $allTasks[1]->description);
        $this->assertEquals(2, $allTasks[1]->priority);

        $this->assertEquals(3, $allTasks[2]->id);
        $this->assertEquals('TODO 3', $allTasks[2]->title);
        $this->assertEquals('Lets write some PHP code', $allTasks[2]->description);
        $this->assertEquals(3, $allTasks[2]->priority);
    }
}

