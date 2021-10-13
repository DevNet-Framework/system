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
    private DbConnection $Connection;

    public function __construct(DbConnection $connection)
    {
        $connector = $connection->getConnector();
        if (!$connector) {
            throw new \Exception("DB connection is closed");
        }

        $connector->beginTransaction();
        $this->Connection = $connection;
    }

    public function commit()
    {
        $poovider = $this->Connection->getConnector();
        if (!$poovider) {
            throw new \Exception("DB connection is closed");
        }

        $poovider->commit();
    }

    public function rollBack()
    {
        $poovider = $this->Connection->getConnector();
        if (!$poovider) {
            throw new \Exception("DB connection is closed");
        }

        $poovider->rollBack();
    }
}
