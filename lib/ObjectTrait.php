<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Async\AsyncFunction;
use DevNet\System\Exceptions\MethodException;
use DevNet\System\Exceptions\PropertyException;

trait ObjectTrait
{
    private ?Type $_type = null;

    public function __get(string $property)
    {
        $accessor = 'get_' . $property;
        if (method_exists($this, $accessor)) {
            $accessor = $this->getType()->getMethod($accessor)->getName();
            $name = substr(strrchr($accessor, '_'), 1);
            if ($name == $property) {
                return $this->$accessor();
            }
        }

        if (method_exists($this, $property)) {
            return [$this, $property];
        }

        $class = get_class($this);
        if (!property_exists($this, $property)) {
            throw new PropertyException("Access to undefined property {$class}::{$property}", 0, 1);
        }

        throw new PropertyException("Access to non-public property {$class}::{$property}", 0, 1);
    }

    public function __set(string $property, $value): void
    {
        $accessor = 'set_' . $property;
        if (method_exists($this, $accessor)) {
            $accessor = $this->getType()->getMethod($accessor)->getName();
            $name = substr(strrchr($accessor, '_'), 1);
            if ($name == $property) {
                $this->$accessor($value);
                return;
            }
        }

        $class = get_class($this);
        if (!property_exists($this, $property)) {
            throw new PropertyException("Access to undefined property {$class}::{$property}", 0, 1);
        }

        throw new PropertyException("Access to non-public property {$class}::{$property}", 0, 1);
    }

    public function __call(string $method, array $args)
    {
        $asyncMethod = 'async_' . $method;
        if (method_exists($this, $asyncMethod)) {
            $action = new AsyncFunction([$this, $asyncMethod]);
            return $action->invokeArgs($args);
        }

        $extender = new Extender($this);
        $extensionMethod = $extender->getMethod($method);
        if ($extensionMethod) {
            array_unshift($args, $this);
            return $extensionMethod->invokeArgs(null, $args);
        }

        $class = get_class($this);
        if (!method_exists($this, $method)) {
            throw new MethodException("Call to undefined method {$class}::{$method}()", 0, 1);
        }

        throw new MethodException("Call to non-public method {$class}::{$method}()", 0, 1);
    }

    protected function setGenericType(array $typeArguments): void
    {
        if (!$this->_type) {
            $this->_type = new Type(self::class, $typeArguments);
        }
    }

    /**
     * Get the type of the current object.
     */
    public function getType(): Type
    {
        if (!$this->_type) {
            $this->_type = new Type(self::class);
        }

        return $this->_type;
    }

    /**
     * Get a hash code id for the current object
     */
    public function getHashCode(): string
    {
        return spl_object_hash($this);
    }
}
