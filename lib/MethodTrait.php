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
    public function __call(string $method, array $args)
    {
        $asyncMethod = 'async_' . $method;
        if (method_exists($this, $asyncMethod)) {
            $action = new AsyncFunction([$this, $asyncMethod]);
            return $action->invoke($args);
        }

        $extender = new Extender($this);
        $extensionMethod = $extender->getMethod($method);
        if ($extensionMethod) {
            array_unshift($args, $this);
            return $extensionMethod->invokeArgs(null, $args);
        }

        if (method_exists($this, $method)) {
            $modifier = 'private';
            $methodInfo = new ReflectionMethod($this, $method);
            if ($methodInfo->isProtected()) {
                $modifier = 'protected';
            }

            throw new MethodException("Call to {$modifier} method " . static::class . "::{$method}()", 0, 1);
        }

        throw new MethodException("Call to undefined method "  . static::class . "::{$method}()", 0, 1);
    }

    public function __invoke(...$args): Task
    {
        if (method_exists($this, "async_invoke")) {
            $action = new AsyncFunction([$this, "async_invoke"]);
            return $action->invoke($args);
        }

        throw new \Exception("Can not invoke object of type ". $this::class);
    }
}
