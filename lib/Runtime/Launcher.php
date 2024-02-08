<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Runtime;

use DevNet\System\Exceptions\ClassException;
use DevNet\System\Exceptions\MethodException;
use DOMDocument;
use ReflectionMethod;

class Launcher extends LauncherProperties
{
    public function __construct(ClassLoader $loader)
    {
        static::$classLoader = $loader;
    }

    public function launch(array $args = [], ?string $mainClass = null): void
    {
        static::$classLoader->map('/');
        static::$classLoader->register();

        self::$arguments = $args;
        if ($mainClass) {
            static::$startupObject = $mainClass;
        }

        if (!class_exists(static::$startupObject)) {
            throw new ClassException("Could not find the entry point class: " . static::$startupObject, 0, 1);
        }

        if (!method_exists(static::$startupObject, 'main')) {
            throw new MethodException(static::$startupObject . " does not contain a static method 'main' to be suitable for an entry point!", 0, 1);
        }

        static::$entryPoint = new ReflectionMethod(static::$startupObject, 'main');
        static::$entryPoint->invoke(null, $args);
    }

    public static function initialize(string $projectPath): ?static
    {
        if (!is_file($projectPath)) {
            return null;
        }

        $dom = new DOMDocument();
        $result = $dom->load($projectPath);
        if (!$result) {
            return null;
        }

        static::$rootDirectory = dirname($projectPath);

        $rootNamespace = $dom->getElementsByTagName('RootNamespace')->item(0);
        static::$rootNamespace = $rootNamespace ? $rootNamespace->textContent : 'Application';

        $startupObject = $dom->getElementsByTagName('StartupObject')->item(0);
        static::$startupObject = $startupObject ? $startupObject->textContent : 'Application\\Program';

        // Loads external php files if they exist.
        $codeFiles = $dom->getElementsByTagName('CodeFile');
        foreach ($codeFiles as $codeFile) {
            $file = $codeFile->getAttribute('include');
            if (is_file(static::$rootDirectory . '/' . $file)) {
                @include_once static::$rootDirectory . '/' . $file;
            }
        }

        return new static(new ClassLoader(static::$rootDirectory));
    }
}
