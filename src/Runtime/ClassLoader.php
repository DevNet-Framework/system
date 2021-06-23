<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Runtime;

class ClassLoader
{
    private string $Workspace;
    private array $Map;

    public function __construct(string $workspace = null, array $map = [])
    {
        $this->Workspace = $workspace;
        $this->Map = $map;
    }

    public function setWorkspace(string $workspace) : void
    {
        $this->Workspace = $workspace;
    }

    public function map(string $prefix, string $root) : void
    {
        $this->Map[$root] = $prefix;
    }

    public function load(string $type) : void
    {
        $segmets    = explode("\\", $type);
        $name       = array_pop($segmets);
        $namespace  = implode("\\", $segmets);

        foreach ($this->Map as $root => $prefix)
        {
            $position = strpos($namespace, $prefix);

            if ($position !== false)
            {
                $directory  = substr_replace($namespace, "", $position, strlen($prefix));
                $directory  = str_replace("\\", "/", $directory);
                $directory  = trim($directory, "/");
                $path       = $this->Workspace.$root."/".$directory."/".$name.".php";

                if (file_exists($path))
                {
                    require_once $path;
                }

                break;
            }
        }

        $directory  = str_replace("\\", "/", $namespace);
        $directory  = trim($directory, "/");
        $path       = $this->Workspace."/".$directory."/".$name.".php";

        if (file_exists($path))
        {
            require_once $path;
        }
    }

    public function register() : void
    {
        spl_autoload_register([$this, 'load']);
    }

    public function unRegister() : void
    {
        spl_autoload_unregister([$this, 'load']);
    }
}
