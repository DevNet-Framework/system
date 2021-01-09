<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Database;

use PDOStatement;
use PDO;

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
        if ($this->Connection->getState() == 0) {
            throw new \Exception("DB connection is closed");
        }

        if ($this->Parameters) {
            $statement = $this->Connection->getConnector()->prepare($this->Sql);
            $statement->execute($this->Parameters);
            return $statement->rowCount();
        } else {
            return $this->Connection->getConnector()->exec($this->Sql);
        }
    }

    public function executeReader(string $objectType = null, array $injection = []) : ?DbReader
    {
        if ($this->Connection->getState() == 0) {
            throw new \Exception("DB connection is closed");
        }

        if ($this->Parameters) {
            $statement = $this->Connection->getConnector()->prepare($this->Sql);
            $statement->execute($this->Parameters);
        } else {
            $statement = $this->Connection->getConnector()->query($this->Sql);
        }

        if (!$statement)
        {
            return null;
        }

        if ($statement->rowCount() == 0) {
            return null;
        }

        $this->Statement = $statement;

        if ($objectType) {
            $statement->setFetchMode(PDO::FETCH_CLASS, $objectType, $injection);
        }
        
        return new DbReader($this);
    }
}