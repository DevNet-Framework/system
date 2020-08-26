<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Database;

class DbTransaction
{
    private DbConnection $Connection;

    public function __construct(DbConnection $connection)
    {
        $this->Connection = $connection;
    }

    public function commit()
    {
        $poovider = $this->Connection->getDbProvider();
        if (!$poovider) {
            throw new \Exception("DB connection is closed");
        }

        $poovider->commit();
    }

    public function rollBack()
    {
        $poovider = $this->Connection->getDbProvider();
        if (!$poovider) {
            throw new \Exception("DB connection is closed");
        }

        $poovider->rollBack();
    }
}
