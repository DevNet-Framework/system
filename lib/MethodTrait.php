<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Async\AsyncFunction;
use DevNet\System\Async\Task;
use DevNet\System\Exceptions\MethodException;
use ReflectionMethod;

trait MethodTrait
{
    public function __call(string $methodName, array $args)
    {
        if (!method_exists($this, $methodName)) {
            $asyncMethod = 'async_' . $methodName;
            if (method_exists($this, $asyncMethod)) {
                $action = new AsyncFunction([$this, $asyncMethod]);
                return $action->invoke($args);
            }

            $extension = Extender::getExtension($this, $methodName);
            if ($extension) {
                array_unshift($args, $this);
                return $extension::$methodName(...$args);
            }

            throw new MethodException("Call to undefined method "  . static::class . "::{$methodName}()", 0, 1);
        }

        $method = new ReflectionMethod($this, $methodName);
        $modifier = 'private';
        if ($method->isProtected()) {
            $modifier = 'protected';
        }

        throw new MethodException("Call to {$modifier} method " . static::class . "::{$methodName}()", 0, 1);
    }

    public function __invoke(...$args): Task
    {
        if (method_exists($this, "async_invoke")) {
            $action = new AsyncFunction([$this, "async_invoke"]);
            return $action->invoke($args);
        }

        throw new MethodException("Can not invoke object of type " . $this::class, 0, 1);
    }
}
