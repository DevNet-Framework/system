<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Runtime\Boot;

use Composer\Autoload\ClassLoader;

class Launcher extends LauncherProperties
{
    private static ?Launcher $Instance = null;

    public static function getLauncher() : Launcher
    {
        if (!self::$Instance)
        {
            self::$Instance = new self;
        }

        return self::$Instance;
    }

    public function setLoader(ClassLoader $loader) : void
    {
        self::$Loader = new Loader($loader);
    }

    public function workspace(string $workspace) : void
    {
        self::$Workspace = $workspace;
    }

    public function namespace(string $namespace) : void
    {
        self::$Namespace = $namespace;
    }

    public function entryPoint(string $entryPoint) : void
    {
        self::$EntryPoint = $entryPoint;
    }

    public function Arguments(array $args) : void
    {
        self::$Arguments = $args;
    }

    public function environmoment(string $env) : void
    {
        self::$Env = $env;
    }
    
    public function Launch() : void
    {
        $inputArgs = $GLOBALS['argv'] ?? [];
        array_shift($inputArgs);
        $inputArgs = $inputArgs + self::$Arguments;

        $runner = new MainClassRunner(self::$EntryPoint, $inputArgs);
        $runner->run();
    }
}
