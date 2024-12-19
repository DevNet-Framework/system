<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

use DevNet\System\Runtime\Launcher;

require_once dirname(__FILE__, 3) . '/autoload.php';

// Load the local composer autoload if exit.
if (is_file($root . '/vendor/autoload.php')) {
    require_once $root . '/vendor/autoload.php';
}

// Get the console arguments without command name
$args = $GLOBALS['argv'] ?? [];
array_shift($args);

// Initialize and launch the application
$launcher = Launcher::initialize($root . '/devnet.proj');
$launcher->launch($args);
