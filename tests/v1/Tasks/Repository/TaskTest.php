<?php

namespace Test\Docler\v1\Task\Repository;

use Docler\Application\Database\Factory;
use Docler\v1\Task\Entity\Task as TaskEntities;
use Docler\v1\Task\Repository\Task as TaskRepository;
use Laminas\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    protected static ?Factory $dbh;

    public static function setUpBeforeClass(): void
    {
        $adapter = new Adapter([
            'driver' => 'Pdo_Pgsql',
            'hostname' => '127.0.0.1',
            'database' => 'temp_db',
            'username' => 'postgres',
            'password' => 'postgres',
            'port' => 5439,
        ]);
        self::$dbh = new Factory($adapter);
    }

    protected function setUp(): void
    {
        self::$dbh->beginTransaction();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        self::$dbh->rollbackTransaction();
        parent::tearDown();
    }

    public function testSaveTask(): void
    {
        $taskRepository = new TaskRepository(self::$dbh);

        $id = $taskRepository->save(
            'Task 1',
            'Description 1',
            1
        );

        $this->assertIsInt($id);

        $task = $taskRepository->find($id);

        $this->assertInstanceOf(
            TaskEntities::class,
            $task
        );
        $this->assertEquals('Task 1', $task->title);
        $this->assertEquals('Description 1', $task->description);
        $this->assertEquals(1, $task->priority);
    }

    public function testDeleteTask(): void
    {
        $taskRepository = new TaskRepository(self::$dbh);

        $id = $taskRepository->save(
            'Task 1',
            'Description 1',
            1
        );

        $taskRepository->delete($id);

        $deletedTask = $taskRepository->find($id);

        $this->assertNull($deletedTask);
    }

    public function testUpdateTask(): void
    {
        $taskRepository = new TaskRepository(self::$dbh);

        $id = $taskRepository->save(
            'Task 1',
            'Description 1',
            1
        );

        $taskRepository->update(
            $id,
            'Task 2',
            'Description 2',
            999
        );

        $task = $taskRepository->find($id);

        $this->assertEquals('Task 2', $task->title);
        $this->assertEquals('Description 2', $task->description);
        $this->assertEquals(999, $task->priority);
    }

    public function testFindTask(): void
    {
        $taskRepository = new TaskRepository(self::$dbh);

        $id = $taskRepository->save(
            'Task 1',
            'Description 1',
            1
        );

        $task = $taskRepository->find($id);

        $this->assertEquals('Task 1', $task->title);
        $this->assertEquals('Description 1', $task->description);
        $this->assertEquals(1, $task->priority);
    }

    public function testAllTask(): void
    {
        $taskRepository = new TaskRepository(self::$dbh);

        $taskRepository->save(
            'Task 1',
            'Description 1',
            1
        );
        $taskRepository->save(
            'Task 2',
            'Description 2',
            2
        );
        $taskRepository->save(
            'Task 3',
            'Description 3',
            3
        );

        $task = $taskRepository->all();

        $this->assertEquals('Task 1', $task[0]->title);
        $this->assertEquals('Description 1', $task[0]->description);
        $this->assertEquals(1, $task[0]->priority);

        $this->assertEquals('Task 2', $task[1]->title);
        $this->assertEquals('Description 2', $task[1]->description);
        $this->assertEquals(2, $task[1]->priority);

        $this->assertEquals('Task 3', $task[2]->title);
        $this->assertEquals('Description 3', $task[2]->description);
        $this->assertEquals(3, $task[2]->priority);
    }

    public static function tearDownAfterClass(): void
    {
        self::$dbh = null;
    }
}
