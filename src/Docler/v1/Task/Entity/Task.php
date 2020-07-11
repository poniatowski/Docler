<?php

namespace Docler\v1\Task\Entity;

use DateTimeImmutable;
use JsonSerializable;
use stdClass;

class Task implements JsonSerializable
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

    public function jsonSerialize(): stdClass
    {
        $ret = new stdClass();

        $ret->id = $this->id;
        $ret->title = $this->title;
        $ret->description = $this->description;
        $ret->priority = $this->priority;
        $ret->created = $this->created;
        $ret->modified = $this->modified;
        $ret->removed = $this->removed;

        return $ret;
    }
}
