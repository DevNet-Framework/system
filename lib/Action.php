<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use ReflectionFunction;
use Closure;

class Action
{
    private ReflectionFunction $MethodInfo;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(callable $action)
    {
        $action = Closure::fromCallable($action);
        $this->MethodInfo = new ReflectionFunction($action);
    }

    public function __invoke(...$args)
    {
        return $this->invokeArgs($args);
    }

    public function invokeArgs(array $args = [])
    {
        return $this->MethodInfo->invokeArgs($args);
    }
}
