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
    private DbCommand $command;
    private array $row = [];

    public function __construct(DbCommand $command)
    {
        $this->command = $command;
        $this->command->Statement->setFetchMode(PDO::FETCH_ASSOC);
    }

    public function read(): bool
    {
        if ($this->command->Connection->getState() === 1) {
            $row = $this->command->Statement->fetch();

            if ($row) {
                $this->row = $row;
                return true;
            }
        }

        $this->row = [];
        $this->command->Statement->closeCursor();
        return false;
    }

    public function getValue(string $name)
    {
        return $this->row[$name] ?? null;
    }

    public function getName(int $ordinal): ?string
    {
        $row = array_keys($this->row);
        return $row[$ordinal] ?? null;
    }

    public function reset()
    {
        $this->row = [];
        $this->command->Statement->closeCursor();
    }

    public function close()
    {
        $this->row = [];
        $this->command->Statement->closeCursor();
        $this->command->Statement = null;
        $this->command->Connection = null;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->command->Statement->fetchAll());
    }
}
