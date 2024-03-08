<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use DevNet\System\PropertyTrait;

abstract class DbTransaction
{
    use PropertyTrait;

    protected DbConnection $connection;

    public function get_Connection(): ?DbConnection
    {
        return $this->connection ?? null;
    }

    public abstract function commit(): void;

    public abstract function rollBack(): void;
}
