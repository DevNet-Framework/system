<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database\Sqlite;

use DevNet\System\Database\DbConnection;
use PDO;

class SqliteConnection extends DbConnection
{
    public function open(): void
    {
        if ($this->state == 0) {
            $dsn = "sqlite:" . $this->ConnectionString;
            $this->connector = new PDO($dsn);
            $this->state = 1;
        }
    }

    public function beginTransaction(): SqliteTransaction
    {
        return new SqliteTransaction($this, $this->connector);
    }

    public function createCommand(string $sql): SqliteCommand
    {
        return new SqliteCommand($this, $sql);
    }

    public function close(): void
    {
        $this->connector = null;
        $this->state = 0;
    }
}
