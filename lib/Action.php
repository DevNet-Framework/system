<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\PropertyException;
use ReflectionFunction;
use ReflectionMethod;
use Closure;

class Action
{
    protected ReflectionFunction $MethodInfo;

    public function __get(string $name)
    {
        if ($name == 'MethodInfo') {
            return $this->MethodInfo;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(callable $action)
    {
        if (is_array($action)) {
            $reflection = new ReflectionMethod($action[0], $action[1]);
            $action = $reflection->getClosure($action[0]);
        } else if (is_object($action) && !$action instanceof Closure) {
            $reflection = new ReflectionMethod($action, '__invoke');
            $action = $reflection->getClosure($action);
        }

        $this->MethodInfo = new ReflectionFunction($action);
    }

    public function invokeArgs(array $args = [])
    {
        return $this->MethodInfo->invokeArgs($args);
    }

    public function __invoke(...$args)
    {
        return $this->invokeArgs($args);
    }
}
