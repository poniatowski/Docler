<?php

namespace Docler\v1\Task\Repository\Cached;

use Docler\Application\Database\Factory;
use Docler\v1\Task\Entity\Task as TaskEntities;
use Docler\v1\Task\Repository\Task as TaskRepository;

class Task extends TaskRepository
{
    public function __construct(Factory $dbh)
    {
        // TODO add Memcached
        parent::__construct($dbh);
    }

    public function find(int $id): ?TaskEntities
    {
        // TODO cache it
        parent::find($id);
    }

    public function all(): array
    {
        // TODO cache it
        parent::all();
    }
}
