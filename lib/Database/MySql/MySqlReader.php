<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database\MySql;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Database\DbReader;
use PDOStatement;
use PDO;

class MySqlReader extends DbReader
{
    private MySqlCommand $command;
    private PDOStatement $statement;
    private array $row = [];

    public function __construct(MySqlCommand $command, PDOStatement $statement)
    {
        $this->command   = $command;
        $this->statement = $statement;

        $this->statement->setFetchMode(PDO::FETCH_ASSOC);
    }

    public function read(): bool
    {
        if ($this->command->Connection->State == 1) {
            $row = $this->statement->fetch();

            if ($row) {
                $this->row = $row;
                return true;
            }
        }

        $this->close();
        return false;
    }

    public function getName(int $ordinal): ?string
    {
        $index = 0;
        foreach ($this->row as $name => $value) {
            if ($ordinal == $index) {
                return $name;
            }
            $index++;
        }

        return null;
    }

    public function getValue(string $name): mixed
    {
        return $this->row[$name] ?? null;
    }

    public function close(): void
    {
        $this->row = [];
        $this->statement->closeCursor();
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->statement->fetchAll());
    }
}
