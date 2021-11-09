<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use DevNet\System\Collections\Enumerator;
use IteratorAggregate;
use PDO;

class DbReader implements IteratorAggregate
{
    private DbCommand $Command;
    private array $Row = [];

    public function __construct(DbCommand $command)
    {
        $this->Command = $command;
        $this->Command->Statement->setFetchMode(PDO::FETCH_ASSOC);
    }

    public function read(): bool
    {
        if ($this->Command->Connection->getState() === 1) {
            $row = $this->Command->Statement->fetch();

            if ($row) {
                $this->Row = $row;
                return true;
            }
        }

        $this->Row = [];
        $this->Command->Statement->closeCursor();
        return false;
    }

    public function getValue(string $name)
    {
        return $this->Row[$name] ?? null;
    }

    public function getName(int $ordinal): ?string
    {
        $row = array_keys($this->Row);
        return $row[$ordinal] ?? null;
    }

    public function reset()
    {
        $this->Row = [];
        $this->Command->Statement->closeCursor();
    }

    public function close()
    {
        $this->Row = [];
        $this->Command->Statement->closeCursor();
        $this->Command->Statement = null;
        $this->Command->Connection = null;
    }

    public function getIterator(): iterable
    {
        return new Enumerator($this->Command->Statement->fetchAll());
    }
}
