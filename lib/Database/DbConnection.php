<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use PDO;

abstract class DbConnection
{
    protected string $connectionString;
    protected ?PDO $connector = null;
    protected int $state = 0;

    public string $ConnectionString { get => $this->connectionString; }
    public ?PDO $Connector { get => $this->connector; }
    public int $State { get => $this->state; }

    public function __construct(string $connectionString)
    {
        $this->connectionString = $connectionString;
    }

    public abstract function open(): void;

    public abstract function beginTransaction(): DbTransaction;

    public abstract function createCommand(string $sql): DbCommand;

    public abstract function close(): void;
}
