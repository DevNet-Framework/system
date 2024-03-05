<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\ClassException;
use ReflectionClass;
use Closure;

class Activator
{
    public static function CreateInstance(string $type, array $args = [], ?Closure $binder = null): object
    {
        if (!class_exists($type)) {
            throw new ClassException("Could not find class {$type}", 0, 1);
        }

        $class = new ReflectionClass($type);
        $constructor = $class->getConstructor();

        if ($binder) {
            $params = $constructor->getParameters();
            $args = $binder($args, $params);
        }

        return $class->newInstanceArgs($args);
    }
}
