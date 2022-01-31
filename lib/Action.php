<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use Opis\Closure\SerializableClosure;
use ReflectionFunction;
use Closure;

class Action
{
    private ReflectionFunction $MethodInfo;
    private string $Syntax = '';

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(callable $action)
    {
        $action = Closure::fromCallable($action);
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

    public function  __serialize(): array
    {
        $wrapper = new SerializableClosure($this->MethodInfo->getClosure());
        $serialized = serialize($wrapper);
        return ['Syntax' => $serialized];
    }

    public function __unserialize(array $data): void
    {
        $syntax  = $data['Syntax'];
        $wrapper = unserialize($syntax);
        $closure = $wrapper->getClosure();
        $this->MethodInfo = new ReflectionFunction($closure);
    }
}
