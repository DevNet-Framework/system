<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Runtime;

class ClassLoader
{
    private string $root;
    private array $map;

    public function __construct(string $root, array $map = [])
    {
        $this->root = $root;
        $this->map = $map;
    }

    public function getRoot(): string
    {
        return $this->root;
    }

    public function map(string $prefix, string $root): void
    {
        $this->map[$root] = $prefix;
    }

    public function load(string $class): void
    {
        $segmets   = explode("\\", $class);
        $name      = array_pop($segmets);
        $namespace = implode("\\", $segmets);

        foreach ($this->map as $root => $prefix) {
            $position = strpos($namespace, $prefix);

            if ($position !== false) {
                $directory = substr_replace($namespace, "", $position, strlen($prefix));
                $directory = str_replace("\\", "/", $directory);
                $directory = trim($directory, "/");
                $path      = $this->root . $root . "/" . $directory . "/" . $name . ".php";

                if (file_exists($path)) {
                    require_once $path;
                }

                break;
            }
        }

        array_shift($segmets);
        $directory = implode("\\", $segmets);
        $directory = str_replace("\\", "/", $directory);
        $directory = trim($directory, "/");
        $path      = $this->root . "/" . $directory . "/" . $name . ".php";

        if (file_exists($path)) {
            require_once $path;
        }
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'load']);
    }

    public function unRegister(): void
    {
        spl_autoload_unregister([$this, 'load']);
    }
}
