<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

use DevNet\System\Runtime\ClassLoader;

require_once __DIR__ . '/lib/Runtime/ClassLoader.php';

$loader = new ClassLoader(dirname(__DIR__));

$loader->mapNamespace('DevNet\\System', 'system/lib/');
$loader->mapNamespace('DevNet\\Core', 'core/lib/');
$loader->mapNamespace('DevNet\\Common', 'common/lib/');
$loader->mapNamespace('DevNet\\Http', 'http/lib/');
$loader->mapNamespace('DevNet\\Security', 'security/lib/');
$loader->mapNamespace('DevNet\\ORM', 'orm/lib/');
$loader->mapNamespace('DevNet\\ORM\\MySql', 'orm/lib/Providers/MySql/');
$loader->mapNamespace('DevNet\\ORM\\PgSql', 'orm/lib/Providers/PgSql/');
$loader->mapNamespace('DevNet\\ORM\\Sqlite', 'orm/lib/Providers/Sqlite/');
$loader->include(__DIR__ . '/global.php');

$loader->register();

