<?php

namespace Docler\v1\Task\Model\Cached;

use Docler\v1\Task\Repository\Task as TaskMapper;
use Docler\v1\Task\Entity\Task as TaskEntities;
use Docler\v1\Task\Model\Task as TaskModel;

class Task extends TaskModel
{
    public function __construct(TaskMapper $taskMapper)
    {
        // TODO add Memcached
        parent::__construct($taskMapper);
    }

    public function find(int $id): ?TaskEntities
    {
        // TODO wrap it with cache
        parent::find($id);
    }

    public function all()
    {
        // TODO wrap it with cache
        parent::all();
    }
}
