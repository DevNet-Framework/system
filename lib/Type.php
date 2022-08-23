<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\ArrayException;
use DevNet\System\Exceptions\PropertyException;
use ReflectionMethod;
use ReflectionProperty;

class Type
{
    private string $name;
    private array $arguments  = [];
    private array $properties = [];
    private array $methods    = [];

    public function __get(string $property)
    {
        if ($property == 'Name') {
            return $this->property;
        }

        $class = get_class($this);
        if (!property_exists($this, $property)) {
            throw new PropertyException("Access to undefined property {$class}::{$property}", 0, 1);
        }

        throw new PropertyException("Access to non-public property {$class}::{$property}", 0, 1);
    }

    public function __construct(string $name, array $arguments = [])
    {
        switch (strtolower($name)) {
            case 'boolean':
            case 'bool':
                $name = 'boolean';
                break;
            case 'integer':
            case 'int':
                $name = 'integer';
                break;
            case 'float':
            case 'double':
                $name = 'float';
                break;
            case 'string':
                $name = 'string';
                break;
            case 'array':
                $name = 'array';
                break;
            case 'object':
                $name = 'object';
                break;
            default:
                $name = $name;
                break;
        }

        $this->name = $name;

        foreach ($arguments as $argument) {
            if (!is_string($argument)) {
                $type = gettype($argument);
                throw new ArrayException("Generic type arguments must be defind by an array of string values, {$type} was given", 0, 1);
            }

            $this->arguments[] = new Type($argument);
        }
    }

    public function makeGenericType(array $typeArguments): Type
    {
        return new Type($this->name, $typeArguments);
    }

    public function getGenericArguments(): array
    {
        return $this->arguments;
    }

    public function getProperty(string $property): ?ReflectionProperty
    {
        if (isset($this->properties[$property])) return $this->properties[$property];

        if ($this->isClass()) {
            if (property_exists($this->name, $property)) {
                $propertyInfo = new ReflectionProperty($this->name, $property);
                $this->properties[$property] = $propertyInfo;
                return $propertyInfo;
            }
        }

        return null;
    }

    public function getMethod(string $method): ?ReflectionMethod
    {
        if (isset($this->methods[$method])) return $this->methods[$method];

        if ($this->isClass()) {
            if (method_exists($this->name, $method)) {
                $methodInfo = new ReflectionMethod($this->name, $method);
                $this->methods[$method] = $methodInfo;
                return $methodInfo;
            }
        }

        return null;
    }

    public function isPrimitive(): bool
    {
        $types = ['boolean', 'integer', 'float', 'string'];
        if (in_array($this->name, $types)) return true;

        return false;
    }

    public function isInterface(): bool
    {
        return interface_exists($this->name);
    }

    public function isClass(): bool
    {
        return class_exists($this->name);
    }

    public function isGeneric(): bool
    {
        if ($this->isClass() && $this->arguments) return true;

        return false;
    }

    public function isSubclassOf(Type $class): bool
    {
        return is_subclass_of($this->name, $class->Name);
    }

    public function isEquivalentTo(Type $type): bool
    {
        return $this == $type;
    }

    public function isOfType($element): bool
    {
        $type = self::getType($element);

        if ($this->isEquivalentTo($type)) return true;

        if ($type->isSubclassOf($this)) return true;

        if ($this->name == 'object' && $type->isClass()) return true;

        return false;
    }

    public function __toString(): string
    {
        $name = $this->name;
        if ($this->isGeneric()) $name .= '<' . implode(',', $this->arguments) . '>';

        return $name;
    }

    public static function getType($element): Type
    {
        if (is_string($element) && class_exists($element)) return new Type($element);

        $type = gettype($element);
        if ($type == 'object') {
            if (method_exists($element, 'getType')) {
                $method = new \ReflectionMethod($element, 'getType');
                if ($method->hasReturnType() && $method->getReturnType()->getName() == Type::class) {
                    return $element->getType();
                }
            }

            return new Type(get_class($element));
        }

        return new Type($type);
    }
}
