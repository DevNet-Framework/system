<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

class DbTransaction
{
    private DbConnection $connection;

    public function __construct(DbConnection $connection)
    {
        $connector = $connection->getConnector();
        if (!$connector) {
            throw new \Exception("DB connection is closed");
        }

        $connector->beginTransaction();
        $this->connection = $connection;
    }

    public function commit(): void
    {
        $connector = $this->connection->getConnector();
        if (!$connector) {
            throw new \Exception("DB connection is closed");
        }

        if ($connector->inTransaction()) {
            $connector->commit();
        }

    }

    public function rollBack(): void
    {
        $connector = $this->connection->getConnector();
        if (!$connector) {
            throw new \Exception("DB connection is closed");
        }

        if ($connector->inTransaction()) {
            $connector->rollBack();
        }
    }
}
