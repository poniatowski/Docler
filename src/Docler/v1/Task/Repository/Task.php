<?php

namespace Docler\v1\Task\Repository;

use DateTimeImmutable;
use Docler\Application\Database\Factory;
use Docler\v1\Task\Entity\Task as TaskEntities;
use Docler\v1\Task\Interfaces\TaskInterface;

class Task implements TaskInterface
{
    protected Factory $dbh;

    public function __construct(Factory $dbh)
    {
        $this->dbh = $dbh;
    }

    public function save(string $title, string $description, int $priority): int
    {
        $sql = "
            INSERT INTO tasks
             (title, description, priority, created)
            VALUES
             (:title, :description, :priority, now())
            RETURNING id;
        ";
        $binds = [
            'title' => $title,
            'description' => $description,
            'priority' => $priority,
        ];

        return $this->dbh->getFirstRow($sql, $binds)['id'];
    }

    public function delete(int $id): void
    {
        $sql = "
            UPDATE tasks SET
                removed = now()
            WHERE id = :id
        ";
        $binds = [
            'id' => $id,
        ];

        $this->dbh->query($sql, $binds);
    }

    public function update(int $id, string $title, string $description, int $priority): void
    {
        $sql = "
            UPDATE tasks SET 
                title = :title,
                description = :description,
                priority = :priority,
                modified = now()
            WHERE id = :id
        ";
        $binds = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'priority' => $priority,
        ];

        $this->dbh->query($sql, $binds);
    }

    public function find(int $id): ?TaskEntities
    {
        $sql = "
            SELECT *
            FROM tasks
            WHERE id = :id 
              AND removed IS NULL;
        ";
        $binds = [
            'id' => $id
        ];
        $result = $this->dbh->getFirstRow($sql, $binds);

        if ($result === null) {
            return null;
        }

        $removed = null;
        if ($result['removed'] !== null) {
            $removed = new DateTimeImmutable($result['removed']);
        }

        $modified = null;
        if ($result['modified'] !== null) {
            $modified = new DateTimeImmutable($result['modified']);
        }

        return new TaskEntities(
            $result['id'],
            $result['title'],
            $result['description'],
            $result['priority'],
            new DateTimeImmutable($result['created']),
            $modified,
            $removed
        );
    }

    public function all(): array
    {
        $sql = "
            SELECT *
            FROM tasks
            WHERE removed IS NULL
            ORDER BY id;
        ";
        $result = $this->dbh->getAll($sql);

        $tasks = [];
        foreach ($result as $row) {
            $removed = null;
            if ($row['removed'] !== null) {
                $removed = new DateTimeImmutable($row['removed']);
            }

            $modified = null;
            if ($row['modified'] !== null) {
                $modified = new DateTimeImmutable($row['modified']);
            }

            $task = new TaskEntities(
                $row['id'],
                $row['title'],
                $row['description'],
                $row['priority'],
                new DateTimeImmutable($row['created']),
                $modified,
                $removed
            );

            $tasks[] = $task;
        }

        return $tasks;
    }
}
