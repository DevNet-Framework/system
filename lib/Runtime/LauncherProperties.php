<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Runtime;

use ReflectionMethod;

class LauncherProperties
{
    protected static ?ClassLoader $classLoader = null;
    protected static ?ReflectionMethod $entryPoint = null;
    protected static string $startupObject = 'Application\Program';
    protected static string $rootNamespace = 'Application';
    protected static string $rootDirectory = __DIR__;
    protected static string $sourceRoot = 'src';
    protected static array $arguments = [];

    public static function getLoader(): ?ClassLoader
    {
        return static::$classLoader ?? null;
    }

    public static function getEntryPoint(): ?ReflectionMethod
    {
        return static::$entryPoint ?? null;
    }

    public static function getStartupObject(): string
    {
        return static::$startupObject;
    }

    public static function getRootNamespace(): string
    {
        return static::$rootNamespace;
    }

    public static function getRootDirectory(): string
    {
        return static::$rootDirectory;
    }

    public static function getSourceRoot(): string
    {
        return static::$sourceRoot;
    }

    public static function getArguments(): array
    {
        return static::$arguments;
    }
}
