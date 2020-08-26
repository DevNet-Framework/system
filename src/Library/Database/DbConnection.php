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
    private string $DataSource;
    private string $Username;
    private string $Password;
    private int $State = 0;
    private ?PDO $DataProvider;

    public function __construct(string $connection)
    {
        $driver     = parse_url($connection, PHP_URL_SCHEME);
        $host       = parse_url($connection, PHP_URL_HOST);
        $dbname     = parse_url($connection, PHP_URL_PATH);
        $port       = parse_url($connection, PHP_URL_PORT);

        $dbname = ltrim($dbname, '/');
        $port = $port ? ":{$port}" : null;
        
        $this->DataSource = "{$driver}:host={$host}{$port};dbname={$dbname}";
        $this->Username   = parse_url($connection, PHP_URL_USER);
        $this->Password   = parse_url($connection, PHP_URL_PASS);
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