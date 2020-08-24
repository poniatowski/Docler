<?php

namespace Docler\v1\Task\Entity;

use DateTimeImmutable;

class Task
{
    public int $id;

    public string $title;

    public string $description;

    public int $priority = 0;

    public DateTimeImmutable $created;

    public ?DateTimeImmutable $modified;

    public ?DateTimeImmutable $removed;

    public function __construct(
        int $id,
        string $title,
        string $description,
        int $priority = 0,
        DateTimeImmutable $created,
        DateTimeImmutable $modified = null,
        DateTimeImmutable $removed = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->priority = $priority;
        $this->created = $created;
        $this->modified = $modified;
        $this->removed = $removed;
    }
}
