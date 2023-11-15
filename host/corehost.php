<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

use DevNet\System\Runtime\ClassLoader;

require_once __DIR__ . '/../lib/Runtime/ClassLoader.php';

$loader = new ClassLoader(dirname(__DIR__, 2));

$loader->mapNamespace('DevNet\\System', 'core/lib/');
$loader->mapNamespace('DevNet\\Web', 'web/lib/');
$loader->mapNamespace('DevNet\\Entity', 'entity/lib/');
$loader->mapNamespace('DevNet\\Entity\\MySql', 'entity/lib/Providers/MySql/');
$loader->mapNamespace('DevNet\\Entity\\PgSql', 'entity/lib/Providers/PgSql/');
$loader->mapNamespace('DevNet\\Entity\\Sqlite', 'entity/lib/Providers/Sqlite/');
$loader->include(__DIR__ . '/../lib/Functions.php');

$loader->register();

