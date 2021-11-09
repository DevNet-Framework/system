<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use PDOStatement;

class DbCommand
{
    public ?DbConnection $Connection = null;
    public ?PDOStatement $Statement = null;
    public string $Sql;
    public array $Parameters = [];

    public function __construct(DbConnection $connection, string $sql = null)
    {
        $this->Connection = $connection;
        $this->Sql = $sql;
    }

    public function addParameters(array $parameters)
    {
        $this->Parameters = $parameters;
    }

    public function execute() : int
    {
        if ($this->Connection->getState() === 0)
        {
            throw new \PDOException('Database connection is closed');
        }

        if ($this->Parameters)
        {
            $statement = $this->Connection->getConnector()->prepare($this->Sql);
            $result = $statement->execute($this->Parameters);

            if (!$result) {
                $errorInfo = $statement->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }

            $result = $statement->rowCount();
        }
        else
        {
            $result = $this->Connection->getConnector()->exec($this->Sql);
            if ($result === false) {
                $errorInfo = $this->Connection->getConnector()->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }
        }

        return $result;
    }

    public function executeReader(): DbReader
    {
        if ($this->Connection->getState() == 0) {
            throw new \PDOException('Database connection is closed');
        }

        if ($this->Parameters) {
            $statement = $this->Connection->getConnector()->prepare($this->Sql);
            $result = $statement->execute($this->Parameters);
            if (!$result) {
                $errorInfo = $statement->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }
        } else {
            $statement = $this->Connection->getConnector()->query($this->Sql);
            if (!$statement) {
                $errorInfo = $this->Connection->getConnector()->errorInfo();
                throw new \PDOException("[{$errorInfo[0]}] {$errorInfo[2]}", $errorInfo[1]);
            }
        }

        $this->Statement = $statement;
        return new DbReader($this);
    }
}
