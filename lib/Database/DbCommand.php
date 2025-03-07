<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

abstract class DbCommand
{
    protected DbConnection $connection;
    protected string $sql = '';

    public ?DbConnection $Connection { get => $this->connection; }
    public string $Sql { get => $this->sql; }

    public abstract function execute(array $parameters = []): int;

    public abstract function executeReader(array $parameters = []): DbReader;
}
