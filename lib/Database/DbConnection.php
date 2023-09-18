<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use DevNet\System\PropertyTrait;

abstract class DbConnection
{
    use PropertyTrait;

    protected int $state = 0;

    public function get_State(): int
    {
        return $this->state;
    }

    public abstract function open(): void;

    public abstract function beginTransaction(): DbTransaction;

    public abstract function createCommand(string $sql): DbCommand;

    public abstract function close(): void;
}
