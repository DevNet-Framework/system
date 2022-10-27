<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Tasks\AsyncFunction;
use DevNet\System\Exceptions\MethodException;
use DevNet\System\Exceptions\PropertyException;
use DevNet\System\Exceptions\TypeException;

trait ObjectTrait
{
    private static ?Type $__type = null;

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

        if (property_exists($this, $property)) {
            $modifier = 'private';
            if ($this->getType()->getProperty($property)->isProtected()) {
                $modifier = 'protected';
            }

            throw new PropertyException("Cannot access {$modifier} property " . static::class . "::{$property}", 0, 1);
        }

        throw new PropertyException("Cannot access undefined property " . static::class . "::{$property}", 0, 1);
    }

    public function __set(string $property, $value): void
    {
        $accessor = 'set_' . $property;
        if (method_exists($this, $accessor)) {
            $accessor = $this->getType()->getMethod($accessor)->getName();
            $name = substr(strrchr($accessor, '_'), 1);
            if ($name == $property) {
                try {
                    $this->$accessor($value);
                    return;
                } catch (\TypeError $error) {
                    $type = Type::getType($value);
                    throw new TypeException("Cannot assign a value of type '{$type}' to property " . static::class . "::{$property}", 0, 1);
                }
            }
        }

        if (method_exists($this, 'get_' . $property)) {
            throw new PropertyException("Cannot assign a value to read only property " . static::class . "::{$property}", 0, 1);
        }

        if (property_exists($this, $property)) {
            $modifier = 'private';
            if ($this->getType()->getProperty($property)->isProtected()) {
                $modifier = 'protected';
            }

            throw new PropertyException("Cannot access {$modifier} property " . static::class . "::{$property}", 0, 1);
        }

        throw new PropertyException("Cannot access undefined property " . static::class . "::{$property}", 0, 1);
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

        if (method_exists($this, $method)) {
            $modifier = 'private';
            if ($this->getType()->getMethod($method)->isProtected()) {
                $modifier = 'protected';
            }

            throw new MethodException("Call to {$modifier} method " . static::class . "::{$method}()", 0, 1);
        }

        throw new MethodException("Call to undefined method "  . static::class . "::{$method}()", 0, 1);
    }

    protected function setGenericType(array $typeArguments): void
    {
        if (!static::$__type) {
            static::$__type = new Type(static::class, $typeArguments);
        }
    }

    /**
     * Get the type of the current object.
     */
    public function getType(): Type
    {
        if (!static::$__type) {
            static::$__type = new Type(static::class);
        }

        return static::$__type;
    }

    /**
     * Get a hash code id for the current object
     */
    public function getHashCode(): string
    {
        return spl_object_hash($this);
    }
}
