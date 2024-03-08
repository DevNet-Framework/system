<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database\PgSql;

use DevNet\System\Database\DbCommand;
use PDO;

class PgSqlCommand extends DbCommand
{
    public function __construct(PgSqlConnection $connection, string $sql)
    {
        $this->connection = $connection;
        $this->sql = $sql;
    }

    public function execute(array $parameters = []): int
    {
        if ($this->connection->State === 0) {
            throw new \PDOException('Database connection is closed');
        }

        if ($parameters) {
            $statement = $this->connection->Connector->prepare($this->sql);
            $result = $statement->execute($parameters);

            if (!$result) {
                $errorInfo = $statement->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }

            $result = $statement->rowCount();
        } else {
            $result = $this->connection->Connector->exec($this->sql);
            if ($result === false) {
                $errorInfo = $this->connection->Connector->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }
        }

        return $result;
    }

    public function executeReader(array $parameters = []): PgSqlReader
    {
        if ($this->connection->State == 0) {
            throw new \PDOException('Database connection is closed');
        }

        if ($parameters) {
            $statement = $this->connection->Connector->prepare($this->sql);
            $result = $statement->execute($parameters);
            if (!$result) {
                $errorInfo = $statement->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }
        } else {
            $statement = $this->connection->Connector->query($this->sql);
            if (!$statement) {
                $errorInfo = $this->connection->Connector->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }
        }

        return new PgSqlReader($this, $statement);
    }
}
