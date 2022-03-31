<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use PDO;

class DbConnection
{
    private string $datasource;
    private string $username;
    private string $password;
    private ?PDO $connector;
    private int $state = 0;

    public function __construct(string $datasource, string $username = "", string $password = "")
    {
        $this->datasource = $datasource;
        $this->username   = $username;
        $this->password   = $password;
    }

    public function open()
    {
        if ($this->state == 0) {
            $this->connector = new PDO($this->datasource, $this->username, $this->password);
            $this->state = 1;
        }
    }

    public function getConnector(): PDO
    {
        return $this->connector;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function beginTransaction()
    {
        return new DbTransaction($this);
    }

    public function createCommand(string $sql = null): DbCommand
    {
        return new DbCommand($this, $sql);
    }

    public function close()
    {
        $this->connector = null;
        $this->state = 0;
    }
}
