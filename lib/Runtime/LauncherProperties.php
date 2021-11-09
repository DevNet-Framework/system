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
    protected static ClassLoader $Loader;
    protected static string $Workspace = __DIR__;
    protected static ?string $Namespace = 'Application';
    protected static string $EntryPoint = 'Program';
    protected static array $Arguments = [];
    protected static string $Envirement;

    public static function getLoader(): ?ClassLoader
    {
        return self::$Loader ?? null;
    }

    public static function getWorkspace(): string
    {
        return self::$Workspace;
    }

    public static function getNamespace(): string
    {
        return self::$Namespace;
    }

    public static function getEntryPoint(): string
    {
        return self::$EntryPoint;
    }

    public static function getArguments(): array
    {
        return self::$Arguments;
    }

    public static function getEnvironmoment(): string
    {
        return self::$Envirement;
    }
}
