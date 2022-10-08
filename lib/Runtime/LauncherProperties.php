<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Runtime;

class LauncherProperties
{
    protected static ?ClassLoader $classLoader = null;
    protected static string $rootDirectory = __DIR__;
    protected static string $entryPoint = 'Application\Program';
    protected static array $arguments = [];

    public static function getLoader(): ?ClassLoader
    {
        return static::$classLoader ?? null;
    }

    public static function getRootDirectory(): string
    {
        return static::$rootDirectory;
    }

    public static function getEntryPoint(): string
    {
        return static::$entryPoint;
    }

    public static function getArguments(): array
    {
        return static::$arguments;
    }
}
