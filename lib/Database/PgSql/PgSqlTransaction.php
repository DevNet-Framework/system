<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database\PgSql;

use DevNet\System\Database\DbTransaction;
use PDOException;

class PgSqlTransaction extends DbTransaction
{
    public function __construct(PgSqlConnection $connection)
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
