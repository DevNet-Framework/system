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
    private object $target;
    private static array $classes;

    public function __construct(object $target)
    {
        $this->target = $target;
    }

    public function getMethod(string $method): ?ReflectionMethod
    {
        foreach ($this->getUsedClasses() as $class) {
            if (class_exists($class)) {
                if (method_exists($class, $method)) {
                    $reflectionMethod = new ReflectionMethod($class, $method);
                    $reflectionParams = $reflectionMethod->getParameters();
                    if (isset($reflectionParams[0])) {
                        if ($reflectionParams[0]->hasType()) {
                            $fistParameterType = $reflectionParams[0]->getType()->getName();
                        }

                        if ($this->target instanceof $fistParameterType) {
                            return $reflectionMethod;
                        }
                    }
                }
            }
        }

        return null;
    }

    function getUsedClasses(): array
    {
        $file = '';
        $count = 0;
        $trace = debug_backtrace();
        foreach ($trace as $info) {
            $class = $info['class'] ?? null;
            $function = $info['function'] ?? null;
            if ($class == get_class($this->target) && $function == '__call') {
                $file = $info['file'] ?? null;
                break;
            }
            $count++;
        }

        $classes = self::$classes[$file] ?? [];
        if ($classes) return $classes;

        $callerClass = $trace[$count + 1]['class'] ?? null;
        if ($callerClass) {
            $classes[] = $callerClass;
            self::$classes[$file] = $classes;
        }

        $contents = file_get_contents($file);
        preg_match_all("%(?i)use\s+([A-Za-z_\\\]+);%", $contents, $matches);

        if (isset($matches[1])) {
            $classes = array_merge($classes, $matches[1]);
            self::$classes[$file] = $classes;
        }

        return $classes;
    }
}
