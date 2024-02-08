<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use DevNet\System\PropertyTrait;
use PDO;

abstract class DbConnection
{
    use PropertyTrait;

    protected string $connectionString;
    protected ?PDO $connector = null;
    protected int $state = 0;

    public function __construct(string $connectionString)
    {
        $this->connectionString = $connectionString;
    }

    public function get_ConnectionString(): string
    {
        return $this->connectionString;
    }

    public function get_Connector(): ?PDO
    {
        return $this->connector;
    }

    public function get_State(): int
    {
        return $this->state;
    }

    public abstract function open(): void;

    public abstract function beginTransaction(): DbTransaction;

    public abstract function createCommand(string $sql): DbCommand;

    public abstract function close(): void;
}
