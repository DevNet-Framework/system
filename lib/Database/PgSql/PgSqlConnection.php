<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database\PgSql;

use DevNet\System\Database\DbConnection;
use DevNet\System\Database\DbConnectionStringBuilder;
use PDO;

class PgSqlConnection extends DbConnection
{
    public function open(): void
    {
        if ($this->state == 0) {
            $parser = new DbConnectionStringBuilder();
            $parser->ConnectionString = $this->ConnectionString;
            $dsn = new DbConnectionStringBuilder();

            foreach ($parser as $key => $value) {
                switch (strtolower($key)) {
                    case 'hostname':
                    case 'host':
                        $dsn['host'] = $value;
                        break;
                    case 'database':
                    case 'dbname':
                        $dsn['dbname'] = $value;
                        break;
                    case 'username':
                    case 'user':
                        $username = $value;
                        break;
                    case 'password':
                        $password = $value;
                        break;
                    default:
                        $dsn[$key] = $value;
                        break;
                }
            }

            $this->connector = new PDO('pgsql:' . $dsn, $username ?? null, $password ?? null);
            $this->state = 1;
        }
    }

    public function beginTransaction(): PgSqlTransaction
    {
        return new PgSqlTransaction($this, $this->connector);
    }

    public function createCommand(string $sql): PgSqlCommand
    {
        return new PgSqlCommand($this, $sql);
    }

    public function close(): void
    {
        $this->connector = null;
        $this->state = 0;
    }
}
