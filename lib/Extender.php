<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use ReflectionMethod;

class Extender
{
    private static array $classes;

    public static function getExtension(object $target, string $method): ?string
    {
        foreach (static::getImports($target) as $class) {
            if (class_exists($class)) {
                if (method_exists($class, $method)) {
                    $reflectionMethod = new ReflectionMethod($class, $method);
                    $reflectionParams = $reflectionMethod->getParameters();
                    if (isset($reflectionParams[0])) {
                        if ($reflectionParams[0]->hasType()) {
                            $fistParameterType = $reflectionParams[0]->getType()->getName();
                        }

                        if ($target instanceof $fistParameterType) {
                            return $class;
                        }
                    }
                }
            }
        }

        return null;
    }

    public static function getImports(object $target): array
    {
        $file  = '';
        $count = 0;
        $trace = debug_backtrace();
        foreach ($trace as $info) {
            $class = $info['class'] ?? null;
            $function = $info['function'] ?? null;
            // Looking for the file where the target calls the method __call().
            if ($class == get_class($target) && $function == '__call') {
                $file = $info['file'] ?? '';
                break;
            }
            $count++;
        }

        $classes = self::$classes[$file] ?? [];
        // Return the imports if they are already stored.
        if ($classes) return $classes;

        // Add the case where the extension method used inside the extension class.
        $callerClass = $trace[$count + 1]['class'] ?? null;
        if ($callerClass) {
            $classes[] = $callerClass;
            self::$classes[$file] = $classes;
        }

        // Looking for all the imports in the file.
        if (file_exists($file)) {
            $contents = file_get_contents($file);
            preg_match_all("%(?i)use\s+([A-Za-z_\\\]+);%", $contents, $matches);
            if (isset($matches[1])) {
                $classes = array_merge($classes, $matches[1]);
                self::$classes[$file] = $classes;
            }
        }

        return $classes;
    }
}
