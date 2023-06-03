<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database\MySql;

use DevNet\System\Database\DbConnection;
use PDO;

class MySqlConnection extends DbConnection
{
    private string $datasource;
    private string $username;
    private string $password;
    private ?PDO $connector;

    public function __construct(string $connection)
    {
        $username = parse_url($connection, PHP_URL_USER);
        $password = parse_url($connection, PHP_URL_PASS);
        $host     = parse_url($connection, PHP_URL_HOST);
        $port     = parse_url($connection, PHP_URL_PORT);
        $path     = parse_url($connection, PHP_URL_PATH);
        $query    = parse_url($connection, PHP_URL_QUERY);

        $username = $username ? $username : "";
        $password = $password ? $password : "";
        $host     = $host ? "host=" . $host : "";
        $port     = $port ? ":" . $port : "";
        $database = $path ? substr(strrchr($path, "/"), 1) : "";
        $options  = $query ? str_replace("&", ";", $query) . ";" : "";

        $this->datasource = "mysql:" . $host . $port . ";" . "dbname=" . $database . ";" . $options;
        $this->username   = $username;
        $this->password   = $password;
    }

    public function get_Connector(): PDO
    {
        return $this->connector;
    }

    public function open(): void
    {
        if ($this->state == 0) {
            $this->connector = new PDO($this->datasource, $this->username, $this->password);
            $this->state = 1;
        }
    }

    public function beginTransaction(): MySqlTransaction
    {
        return new MySqlTransaction($this, $this->connector);
    }

    public function createCommand(string $sql): MySqlCommand
    {
        return new MySqlCommand($this, $sql);
    }

    public function close(): void
    {
        $this->connector = null;
        $this->state = 0;
    }
}
