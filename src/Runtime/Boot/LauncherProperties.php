<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Runtime\Boot;

class LauncherProperties
{
    protected static ?object $Loader = null;
    protected static string $Workspace;
    protected static string $Namespace = "Application";
    protected static string $EntryPoint = 'Application\Program';
    protected static array $Arguments = [];
    protected static string $Env;

    public static function getLoader() : ?object
    {
        return self::$Loader;
    }

    public static function getWorkspace() : string
    {
        return self::$Workspace;
    }

    public static function getNamespace() : string
    {
        return self::$Namespace;
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
        return self::$Env;
    }
}
