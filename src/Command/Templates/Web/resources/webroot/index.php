<?php declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use Artister\System\Runtime\Boot\launcher;
use Application\Program;

$launcher = launcher::getLauncher();

$launcher->workspace(dirname(__DIR__));
$launcher->entryPoint(Program::class);
$launcher->launch();
