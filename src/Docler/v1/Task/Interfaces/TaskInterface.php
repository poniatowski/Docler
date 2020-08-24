<?php

namespace Docler\v1\Task\Interfaces;

interface TaskInterface
{
    public function save(string $title, string $description, int $priority);
    public function delete(int $id);
    public function update(int $id, string $title, string $description, int $priority);
    public function find(int $id);
    public function all();
}
