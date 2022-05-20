<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use DevNet\System\Exceptions\PropertyException;
use PDOStatement;

class DbCommand
{
    private ?DbConnection $connection = null;
    private ?PDOStatement $statement = null;
    private string $sql;
    private array $parameters = [];

    public function __get(string $name)
    {
        if (in_array($name, ['Connection', 'Statement', 'Sql', 'Parameters'])) {
            $property = lcfirst($name);
            return $this->$property;
        }
        
        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(DbConnection $connection, string $sql = null)
    {
        $this->connection = $connection;
        $this->sql = $sql;
    }

    public function addParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function execute() : int
    {
        if ($this->connection->getState() === 0)
        {
            throw new \PDOException('Database connection is closed');
        }

        if ($this->parameters)
        {
            $statement = $this->connection->getConnector()->prepare($this->sql);
            $result = $statement->execute($this->parameters);

            if (!$result) {
                $errorInfo = $statement->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }

            $result = $statement->rowCount();
        }
        else
        {
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
