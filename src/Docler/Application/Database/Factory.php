<?php

namespace Docler\Application\Database;

use Laminas\Db\Exception\RuntimeException;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\ResultInterface;

class Factory
{
    private Adapter $dbAdapter;

    protected LoggerInterface $logger;

    private bool $inTransaction = false;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getAdapter(): Adapter
    {
        return $this->dbAdapter;
    }

    public function query(string $sql, array $binds = []): ResultInterface
    {
        return $this->getAdapter()
            ->query($sql)
            ->execute($binds);
    }

    public function getFirstRow(string $sql, array $binds = [])
    {
        $result = $this->query($sql, $binds);
        $row = $result->current();

        if (!is_array($row)) {
            return null;
        }

        return $row;
    }

    public function getAll(string $sql, array $binds = []): array
    {
        $result = $this->query($sql, $binds);

        $ret = [];
        foreach ($result as $row) {
            $ret[] = $row;
        }

        return $ret;
    }

    public function inTransaction(): bool
    {
        return $this->inTransaction;
    }

    public function beginTransaction(): Factory
    {
        if ($this->inTransaction()) {
            throw new RuntimeException('Nested transactions are not supported');
        }

        $this->query('BEGIN');

        $this->inTransaction = true;

        return $this;
    }

    public function commitTransaction(): Factory
    {
        if (!$this->inTransaction()) {
            throw new RuntimeException('We ignore attempts to commit non-existing transaction');
        }

        $this->query('COMMIT');
        $this->inTransaction = false;

        return $this;
    }

    public function rollbackTransaction(): Factory
    {
        if (!$this->inTransaction()) {
            throw new RuntimeException('Must call beginTransaction() before you can rollback');
        }

        $this->query('ROLLBACK');
        $this->inTransaction = false;

        return $this;
    }
}
