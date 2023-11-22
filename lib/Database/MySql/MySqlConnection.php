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
    public function open(): void
    {
        if ($this->state == 0) {
            $username = parse_url($this->connectionString, PHP_URL_USER);
            $password = parse_url($this->connectionString, PHP_URL_PASS);
            $host     = parse_url($this->connectionString, PHP_URL_HOST);
            $port     = parse_url($this->connectionString, PHP_URL_PORT);
            $path     = parse_url($this->connectionString, PHP_URL_PATH);
            $query    = parse_url($this->connectionString, PHP_URL_QUERY);

            $username = $username ? $username : "";
            $password = $password ? $password : "";
            $host     = $host ? "host=" . $host : "";
            $port     = $port ? ":" . $port : "";
            $database = $path ? substr(strrchr($path, "/"), 1) : "";
            $options  = $query ? str_replace("&", ";", $query) . ";" : "";

            $dsn = "mysql:" . $host . $port . ";" . "dbname=" . $database . ";" . $options;
            $this->connector = new PDO($dsn, $username, $password);
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
