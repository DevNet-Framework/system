<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use DevNet\System\ObjectTrait;

class DbConnectionStringBuilder
{
    use ObjectTrait;

    private string $connectionString = "";
    public ?string $Driver           = null;
    public ?string $Datasource       = null;
    public ?string $Database         = null;
    public ?string $Charset          = null;
    public ?string $Username         = null;
    public ?string $Password         = null;

    public function __construct(string $connection = null)
    {
        if ($connection) {
            $this->parseUri($connection);
        }
    }

    public function get_ConnectionString(): string
    {
        return $this->connectionString;
    }

    public function parseUri(string $uri): void
    {
        $this->Driver       = parse_url($uri, PHP_URL_SCHEME);
        $this->Username     = parse_url($uri, PHP_URL_USER);
        $this->Password     = parse_url($uri, PHP_URL_PASS);
        $this->Datasource   = parse_url($uri, PHP_URL_HOST);

        $port               = parse_url($uri, PHP_URL_PORT);
        $path               = parse_url($uri, PHP_URL_PATH);
        $query              = parse_url($uri, PHP_URL_QUERY);

        if ($port != null) {
            $this->Datasource .= ":" . $port;
        }

        if ($this->Datasource == null) {
            $this->Datasource = $path;
        }

        if ($path != null) {
            $segments = explode("/", $path);
            $this->Database = array_pop($segments);
        }

        if ($query) {
            parse_str($query, $queries);
            $queries = array_change_key_case($queries);
            $this->Charset = $queries['charset'] ?? null;
        }
    }

    public function build(): string
    {
        $this->connectionString = "";
        $this->connectionString .= $this->Driver . ":";

        switch ($this->Driver) {
            case 'sqlite':
                $this->connectionString .= $this->Datasource;
                break;
            case 'mysql':
                $this->connectionString .= "host=" . $this->Datasource . ";";
                $this->connectionString .= "dbname=" . $this->Database . ";";
                $this->connectionString .= $this->Charset ? "charset=" . $this->Charset . ";" : null;
                $this->connectionString .= "user=" . $this->Username . ";";
                $this->connectionString .= "password=" . $this->Password . ";";
                break;
            case 'pgsql':
                $this->Datasource = str_replace(":", ",", $this->Datasource);
                $this->connectionString .= "host=" . $this->Datasource . ";";
                $this->connectionString .= "dbname=" . $this->Database . ";";
                $this->connectionString .= $this->Charset ? "charset=" . $this->Charset . ";" : null;
                $this->connectionString .= "user=" . $this->Username . ";";
                $this->connectionString .= "password=" . $this->Password . ";";
                break;
            case 'sqlsrv':
                $this->Datasource = str_replace(":", ",", $this->Datasource);
                $this->connectionString .= "server=" . $this->Datasource . ";";
                $this->connectionString .= "database=" . $this->Database . ";";
                $this->connectionString .= $this->Charset ? "charset=" . $this->Charset . ";" : null;
                $this->connectionString .= "user=" . $this->Username . ";";
                $this->connectionString .= "password=" . $this->Password . ";";
                break;
            case 'oci':
                $this->connectionString .= "dbname=//" . $this->Datasource;
                $this->connectionString .= "/" . $this->Database . ";";
                $this->connectionString .= $this->Charset ? "charset=" . $this->Charset . ";" : null;
                $this->connectionString .= "user=" . $this->Username . ";";
                $this->connectionString .= "password=" . $this->Password . ";";
                break;
            default:
                $this->connectionString .= "uri:file://" . $this->Datasource;
                break;
        }

        return $this->connectionString;
    }
}
