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
    private string $Datasource;
    private string $Username;
    private string $Password;
    private ?PDO $Connector;
    private int $State = 0;

    public function __construct(string $datasource, string $username = "", string $password = "")
    {
        $this->Datasource   = $datasource;
        $this->Username     = $username;
        $this->Password     = $password;
    }

    public function open()
    {
        if ($this->State == 0) {
            $this->Connector = new PDO($this->Datasource, $this->Username, $this->Password);
            $this->State = 1;
        }
    }

    public function getConnector(): PDO
    {
        return $this->Connector;
    }

    public function getState(): int
    {
        return $this->State;
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
        $this->Connector = null;
        $this->State = 0;
    }
}
