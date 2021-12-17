<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Loader;

class Launcher extends LauncherProperties
{
    private static ?Launcher $Instance = null;

    public static function getLauncher(): Launcher
    {
        if (!self::$Instance) {
            self::$Instance = new self;
        }

        return self::$Instance;
    }

    public function workspace(string $workspace): void
    {
        self::$Workspace = $workspace;
    }

    public function namespace(string $namespace): void
    {
        self::$Namespace = $namespace;
    }

    public function entryPoint(string $entryPoint): void
    {
        self::$EntryPoint = $entryPoint;
    }

    public function Arguments(array $args): void
    {
        self::$Arguments = $args;
    }

    public function environmoment(string $env): void
    {
        self::$Envirement = $env;
    }

    public function provider(object $provider): void
    {
        self::$Provider = $provider;
    }

    public function Launch(): void
    {
        self::$Loader = new ClassLoader(self::$Workspace, ["/" => self::$Namespace]);
        self::$Loader->register();

        $inputArgs = $GLOBALS['argv'] ?? [];
        array_shift($inputArgs);

        $inputArgs = $inputArgs + self::$Arguments;
        $mainClass = self::$Namespace . '\\' . self::$EntryPoint;
        $runner    = new MainMethodRunner($mainClass, $inputArgs);

        $runner->run();
    }
}
