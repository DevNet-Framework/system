<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Database;
use PDO;

class DbConnection
{
    private string $Datasource;
    private string $Username;
    private string $Password;
    private ?PDO $DataProvider;
    private int $State = 0;

    public function __construct(string $connection)
    {
        preg_match("%user\s*=((\\.|[^;])*)%", $connection, $user);
        if ($user)
        {
            $this->Username = $user[1];
        }

        preg_match("%password\s*=((\\.|[^;])*)%", $connection, $password);
        if ($password)
        {
            $this->Password = $password[1];
        }

        $this->DataSource = preg_replace("%user\s*=(\\.|[^;])*;|password\s*=(\\.|[^;])*;%", "", $connection);
    }

    public function open()
    {
        if ($this->State == 0) {
            $this->DataProvider = new PDO($this->DataSource, $this->Username, $this->Password);
            $this->State = 1;
        }
    }

    public function getDataProvider() : PDO
    {
        return $this->DataProvider;
    }

    public function getState() : int
    {
        return $this->State;
    }

    public function beginTransaction()
    {
        return new DbTransaction($this);
    }

    public function createCommand(string $sql = null) : DbCommand
    {
        return new DbCommand($this, $sql);
    }

    public function close()
    {
        $this->DataProvider = null;
        $this->State = 0;
    }
}