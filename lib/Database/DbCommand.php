<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use DevNet\System\PropertyTrait;

abstract class DbCommand
{
    use PropertyTrait;

    protected DbConnection $connection;
    protected string $sql = '';

    public function get_Connection(): ?DbConnection
    {
        return $this->connection ?? null;
    }

    public function get_Sql(): string
    {
        return $this->sql;
    }

    public abstract function execute(array $parameters = []): int;

    public abstract function executeReader(array $parameters = []): DbReader;
}
