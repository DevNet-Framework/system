<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Runtime;

use DirectoryIterator;

class ClassLoader
{
    private string $root;
    private array $codeFiles = [];

    public function __construct(string $root)
    {
        $this->root = $root;
    }

    public function map(string $path): void
    {
        $this->codeFiles += $this->scanForCodeFiles($this->root . $path);
    }

    public function scanForCodeFiles(string $directory): array
    {
        $files = [];
        foreach (new DirectoryIterator($directory) as $path) {
            if ($path->isDir() && !str_starts_with($path->getFilename(), '.')) {
                $result = $this->scanForCodeFiles($directory . '/' . $path->getFilename());
                $files = array_merge($files, $result);
            } else if ($path->isFile() && strtolower($path->getExtension()) == 'php') {
                $content = file_get_contents($path->getRealPath());
                preg_match_all('%namespace\s+([a-z][a-z0-9_\\\]*)\s*;|class\s+([a-z][a-z0-9_]*)|/\*(.|\n)*?\*/|//.*%i', $content, $matches);

                $namespace = null;
                $namespaces = $matches[1] ?? [];
                foreach ($namespaces as $namespace) {
                    if ($namespace) {
                        $namespace .= '\\';
                        break;
                    }
                }

                $classes = $matches[2] ?? [];
                foreach ($classes as $class) {
                    if ($class) {
                        $files[$namespace . $class] = $path->getRealPath();
                    }
                }
            }
        }

        return $files;
    }

    public function load(string $class): void
    {
        $file = $this->codeFiles[$class] ?? null;
        if ($file) {
            @include_once $file;
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
