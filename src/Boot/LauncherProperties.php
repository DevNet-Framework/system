<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Boot;

class LauncherProperties
{
    protected static ClassLoader $Loader;
    protected static string $Workspace = __DIR__;
    protected static array $Namespaces = ["Application" => "/"];
    protected static string $EntryPoint = "Application\Program";
    protected static array $Arguments = [];
    protected static string $Envirement;

    public static function getLoader() : ?ClassLoader
    {
        return self::$Loader ?? null;
    }

    public static function getWorkspace() : string
    {
        return self::$Workspace;
    }

    public static function getNamespaces() : array
    {
        return self::$Namespaces;
    }

    public function getEntryPoint() : string
    {
        return self::$EntryPoint;
    }
    
    public function getArguments() : array
    {
        return self::$Arguments;
    }

    public function getEnvironmoment() : string
    {
        return self::$Envirement;
    }
}
