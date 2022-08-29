<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use ReflectionFunction;
use ReflectionMethod;
use Closure;

class Action
{
    use ObjectTrait;

    protected ReflectionFunction $function;

    public function __construct(callable $action)
    {
        if (is_array($action)) {
            $reflection = new ReflectionMethod($action[0], $action[1]);
            $action = $reflection->getClosure($action[0]);
        } else if (is_object($action) && !$action instanceof Closure) {
            $reflection = new ReflectionMethod($action, '__invoke');
            $action = $reflection->getClosure($action);
        }

        $this->function = new ReflectionFunction($action);
    }

    public function get_Function(): ReflectionFunction
    {
        return $this->function;
    }

    public function invokeArgs(array $args = [])
    {
        return $this->function->invokeArgs($args);
    }

    public function invoke(...$args)
    {
        return $this->invokeArgs($args);
    }

    public function __invoke(...$args)
    {
        return $this->invokeArgs($args);
    }
}
