<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use DevNet\System\ObjectTrait;
use PDOStatement;

class DbCommand
{
    use ObjectTrait;

    private ?DbConnection $connection = null;
    private ?PDOStatement $statement = null;
    private string $sql;
    private array $parameters = [];

    public function __construct(DbConnection $connection, string $sql = null)
    {
        $this->connection = $connection;
        $this->sql = $sql;
    }

    public function get_Connection(): ?DbConnection
    {
        return $this->connection;
    }

    public function get_Statement(): ?PDOStatement
    {
        return $this->statement;
    }

    public function get_Sql(): string
    {
        return $this->sql;
    }

    public function get_Parameters(): array
    {
        return $this->parameters;
    }

    public function addParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function execute(): int
    {
        if ($this->connection->getState() === 0) {
            throw new \PDOException('Database connection is closed');
        }

        if ($this->parameters) {
            $statement = $this->connection->getConnector()->prepare($this->sql);
            $result = $statement->execute($this->parameters);

            if (!$result) {
                $errorInfo = $statement->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }

            $result = $statement->rowCount();
        } else {
            $result = $this->connection->getConnector()->exec($this->sql);
            if ($result === false) {
                $errorInfo = $this->connection->getConnector()->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }
        }

        return $result;
    }

    public function executeReader(): DbReader
    {
        if ($this->connection->getState() == 0) {
            throw new \PDOException('Database connection is closed');
        }

        if ($this->parameters) {
            $statement = $this->connection->getConnector()->prepare($this->sql);
            $result = $statement->execute($this->parameters);
            if (!$result) {
                $errorInfo = $statement->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }
        } else {
            $statement = $this->connection->getConnector()->query($this->sql);
            if (!$statement) {
                $errorInfo = $this->connection->getConnector()->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }
        }

        $this->statement = $statement;
        return new DbReader($this);
    }
}
