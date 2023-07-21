<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use DevNet\System\Tweak;

abstract class DbTransaction
{
    use Tweak;

    protected DbConnection $connection;

    public function get_Connection(): ?DbConnection
    {
        return $this->connection ?? null;
    }

    public abstract function commit(): void;

    public abstract function rollBack(): void;
}
