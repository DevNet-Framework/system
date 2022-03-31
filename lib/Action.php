<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\PropertyException;
use Opis\Closure\SerializableClosure;
use ReflectionFunction;
use Closure;

class Action
{
    protected ReflectionFunction $MethodInfo;
    private string $syntax = '';

    public function __get(string $name)
    {
        if ($name == 'MethodInfo') {
            return $this->MethodInfo;
        }
        
        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
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
        return ['syntax' => $serialized];
    }

    public function __unserialize(array $data): void
    {
        $syntax  = $data['syntax'];
        $wrapper = unserialize($syntax);
        $closure = $wrapper->getClosure();
        $this->MethodInfo = new ReflectionFunction($closure);
    }
}
