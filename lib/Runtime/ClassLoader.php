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

    public function map(string $namespacePrefix, string $baseDirectory): void
    {
        $this->map[$baseDirectory] = $namespacePrefix;
    }

    public function load(string $class): void
    {
        $segments  = explode("\\", $class);
        $name      = array_pop($segments);
        $namespace = implode("\\", $segments);

        foreach ($this->map as $baseDirectory => $namespacePrefix) {
            $position = strpos($namespace, $namespacePrefix);

            if ($position !== false) {
                $subDirectory = substr_replace($namespace, "", $position, strlen($namespacePrefix));
                $subDirectory = str_replace("\\", "/", $subDirectory);
                $subDirectory = trim($subDirectory, "/");
                $path         = $this->root . $baseDirectory . "/" . $subDirectory . "/" . $name . ".php";

                if (is_file($path)) {
                    require_once $path;
                    return;
                }
            }
        }

        array_shift($segments);
        $directory = implode("\\", $segments);
        $directory = str_replace("\\", "/", $directory);
        $directory = trim($directory, "/");
        $path      = $this->root . "/" . $directory . "/" . $name . ".php";

        if (is_file($path)) {
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
