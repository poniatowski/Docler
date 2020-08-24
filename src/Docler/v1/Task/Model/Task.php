<?php

namespace Docler\v1\Task\Model;

use Docler\v1\Task\Repository\Task as TaskMapper;
use Docler\v1\Task\Entity\Task as TaskEntities;

class Task
{
    protected TaskMapper $taskMapper;

    public function __construct(TaskMapper $taskMapper)
    {
        $this->taskMapper = $taskMapper;
    }

    public function save(string $title, string $description, int $priority): int
    {
        return $this->taskMapper->save($title, $description, $priority);
    }

    public function delete(int $id): void
    {
        $this->taskMapper->delete($id);
    }

    public function update(int $id, string $title, string $description, int $priority): void
    {
        $this->taskMapper->update($id, $title, $description, $priority);
    }

    public function find(int $id): ?TaskEntities
    {
        return $this->taskMapper->find($id);
    }

    public function all(): array
    {
        return $this->taskMapper->all();
    }
}
