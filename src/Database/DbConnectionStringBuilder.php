<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use DevNet\System\Exceptions\PropertyException;

class DbConnectionStringBuilder
{
    private string $ConnectionString = "";
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

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        if ($name == "ConnectionString") {
            $class = self::class;
            throw new PropertyException("read onley property {$class}::ConnectionString");
        }

        $this->$name = $value;
    }

    public function parseUri(string $uri)
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
        $this->ConnectionString = "";
        $this->ConnectionString .= $this->Driver . ":";

        switch ($this->Driver) {
            case 'sqlite':
                $this->ConnectionString .= $this->Datasource;
                break;
            case 'mysql':
                $this->ConnectionString .= "host=" . $this->Datasource . ";";
                $this->ConnectionString .= "dbname=" . $this->Database . ";";
                $this->ConnectionString .= $this->Charset ? "charset=" . $this->Charset . ";" : null;
                $this->ConnectionString .= "user=" . $this->Username . ";";
                $this->ConnectionString .= "password=" . $this->Password . ";";
                break;
            case 'pgsql':
                $this->Datasource = str_replace(":", ",", $this->Datasource);
                $this->ConnectionString .= "host=" . $this->Datasource . ";";
                $this->ConnectionString .= "dbname=" . $this->Database . ";";
                $this->ConnectionString .= $this->Charset ? "charset=" . $this->Charset . ";" : null;
                $this->ConnectionString .= "user=" . $this->Username . ";";
                $this->ConnectionString .= "password=" . $this->Password . ";";
                break;
            case 'sqlsrv':
                $this->Datasource = str_replace(":", ",", $this->Datasource);
                $this->ConnectionString .= "server=" . $this->Datasource . ";";
                $this->ConnectionString .= "database=" . $this->Database . ";";
                $this->ConnectionString .= $this->Charset ? "charset=" . $this->Charset . ";" : null;
                $this->ConnectionString .= "user=" . $this->Username . ";";
                $this->ConnectionString .= "password=" . $this->Password . ";";
                break;
            case 'oci':
                $this->ConnectionString .= "dbname=//" . $this->Datasource;
                $this->ConnectionString .= "/" . $this->Database . ";";
                $this->ConnectionString .= $this->Charset ? "charset=" . $this->Charset . ";" : null;
                $this->ConnectionString .= "user=" . $this->Username . ";";
                $this->ConnectionString .= "password=" . $this->Password . ";";
                break;
            default:
                $this->ConnectionString .= "uri:file://" . $this->Datasource;
                break;
        }

        return $this->ConnectionString;
    }
}
