<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database\Sqlite;

use DevNet\System\Database\DbTransaction;
use PDOException;

class SqliteTransaction extends DbTransaction
{
    public function __construct(SqliteConnection $connection)
    {
        $this->connection = $connection;

        if ($this->connection->State == 0) {
            throw new PDOException('Database connection is closed');
        }

        $this->connection->Connector->beginTransaction();
    }

    public function commit(): void
    {
        if ($this->connection->State == 0) {
            throw new PDOException('Database connection is closed');
        }

        if ($this->connection->Connector->inTransaction()) {
            $this->connection->Connector->commit();
        }
    }

    public function rollBack(): void
    {
        if ($this->connection->State == 0) {
            throw new PDOException('Database connection is closed');
        }

        if ($this->connection->Connector->inTransaction()) {
            $this->connection->Connector->rollBack();
        }
    }
}
