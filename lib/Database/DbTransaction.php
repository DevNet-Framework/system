<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

abstract class DbTransaction
{
    protected DbConnection $connection;

    public ?DbConnection $Connection { get => $this->connection ?? null; }

    public abstract function commit(): void;

    public abstract function rollBack(): void;
}
