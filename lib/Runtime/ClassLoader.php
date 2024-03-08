<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Runtime;

use DirectoryIterator;

class ClassLoader
{
    private string $root;
    private array $classes  = [];
    private array $prefixes = [];
    private array $files    = [];

    public function __construct(string $root)
    {
        $this->root = $root;
    }

    public function map(string $directory, bool $allDirectories = true): void
    {
        $this->classes += $this->scanForCodeFiles($this->root . $directory, $allDirectories);
    }

    function mapNamespace(string $prefix, string $directory): void
    {
        $this->prefixes[trim($prefix, '\\')] = $directory;
    }

    function include(string $path): void
    {
        if (is_file($path) && strtolower(pathinfo($path, PATHINFO_EXTENSION)) == 'php') {
            $this->files[] = $path;
        }
    }

    public function scanForCodeFiles(string $directory, bool $allDirectories): array
    {
        $files = [];
        foreach (new DirectoryIterator($directory) as $path) {
            if ($path->isDir() && !str_starts_with($path->getFilename(), '.')) {
                if ($allDirectories) {
                    $files += $this->scanForCodeFiles($directory . '/' . $path->getFilename(), $allDirectories);
                }
            } else if ($path->isFile() && strtolower($path->getExtension()) == 'php') {
                $content = file_get_contents($path->getRealPath());
                preg_match_all('%namespace\s+([a-z][a-z0-9_\\\]*)\s*;|(class|interface|trait|enum)\s+([a-z][a-z0-9_]*)|/\*(.|\n)*?\*/|//.*%i', $content, $matches);

                $namespace = null;
                $namespaces = $matches[1] ?? [];
                foreach ($namespaces as $namespace) {
                    if ($namespace) {
                        $namespace .= '\\';
                        break;
                    }
                }

                $names = $matches[3] ?? [];
                foreach ($names as $name) {
                    if ($name) {
                        $files[$namespace . $name] = $path->getRealPath();
                    }
                }
            }
        }

        return $files;
    }

    public function load(string $class): void
    {
        $path = $this->classes[$class] ?? null;
        if ($path) {
            @include_once $path;
            return;
        }

        $segments  = explode("\\", $class);
        $className = array_pop($segments);
        $namespace = implode("\\", $segments);

        foreach ($this->prefixes as $prefix => $baseDirectory) {
            $position = strpos($namespace, $prefix);

            if ($position !== false) {
                $subDirectory = substr_replace($namespace, "", $position, strlen($prefix));
                $subDirectory = str_replace("\\", "/", $subDirectory);
                $subDirectory = trim($subDirectory, "/");
                $path         = $this->root . '/' . $baseDirectory . "/" . $subDirectory . "/" . $className . ".php";

                if (is_file($path)) {
                    @include_once $path;
                    return;
                }
            }
        }
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'load']);

        // The code files must be loaded after the auto-loaded classes, because they rely on them.
        foreach ($this->files as $file) {
            @include_once $file;
        }
    }

    public function unRegister(): void
    {
        spl_autoload_unregister([$this, 'load']);
    }
}
