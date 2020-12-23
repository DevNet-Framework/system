<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Boot;

class ClassLoader
{
    private string $Workspace;
    private array $Map;

    public function __construct(string $workspace = null, array $map = [])
    {
        $this->Workspace = $workspace;
        $this->Map = $map;
    }

    public function map(string $prefix, string $root)
    {
        $this->Map[$prefix] = $root;
    }

    public function load(string $type)
    {
        $segmets    = explode("\\", $type);
        $name       = array_pop($segmets);
        $namespace  = implode("\\", $segmets);

        foreach ($this->Map as $prefix => $root)
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

    public function register()
    {
        spl_autoload_register([$this, 'load']);
    }

    public function unRegister()
    {
        spl_autoload_unregister([$this, 'load']);
    }
}
