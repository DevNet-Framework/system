<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
