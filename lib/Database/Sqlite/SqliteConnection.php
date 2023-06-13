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
    private string $datasource;
    private ?PDO $connector;

    public function __construct(string $connection)
    {
        $this->datasource = "sqlite:" . $connection;
    }

    public function get_Connector(): PDO
    {
        return $this->connector;
    }

    public function open(): void
    {
        if ($this->state == 0) {
            $this->connector = new PDO($this->datasource);
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
