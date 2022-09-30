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
    private array $arguments = [];
    private static array $properties = [];
    private static array $methods = [];

    public function __get(string $property)
    {
        if ($property == 'Name') {
            return $this->name;
        }

        $class = get_class($this);
        if (!property_exists($this, $property)) {
            throw new PropertyException("Access to undefined property {$class}::{$property}", 0, 1);
        }

        throw new PropertyException("Access to non-public property {$class}::{$property}", 0, 1);
    }

    public function __construct(string $name, array $arguments = [])
    {
        // normalizing the built-in type names
        switch (strtolower($name)) {
            case 'null':
                $name = 'null';
                break;
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
            case 'object':
                $name = 'object';
                break;
            case 'callable':
                $name = 'callable';
                break;
        }

        // the remaining case is considered a class and must be in PascalCase
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
        if (isset(self::$properties[$this->name][$property])) 
        return self::$properties[$this->name][$property];

        if ($this->isClass()) {
            if (property_exists($this->name, $property)) {
                $propertyInfo = new ReflectionProperty($this->name, $property);
                self::$properties[$this->name][$property] = $propertyInfo;
                return $propertyInfo;
            }
        }

        return null;
    }

    public function getMethod(string $method): ?ReflectionMethod
    {
        // use method name in lower case as array key to avoid duplication
        $method = strtolower($method);
        if (isset(self::$methods[$this->name][$method])) return self::$methods[$this->name][$method];

        if ($this->isClass()) {
            if (method_exists($this->name, $method)) {
                $methodInfo = new ReflectionMethod($this->name, $method);
                self::$methods[$this->name][$method] = $methodInfo;
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

    public function isSubclassOf(Type $class): bool
    {
        return is_subclass_of($this->name, $class->Name);
    }

    public function isGeneric(): bool
    {
        if ($this->isClass() && $this->arguments) return true;

        return false;
    }

    public function isEquivalentTo(Type $type): bool
    {
        if ($this == $type) return true;
        if ($this->name == 'object' && $type->isClass()) return true;
        if ($type->Name == 'object' && $this->isClass()) return true;

        return false;
    }

    public function isTypeOf($element): bool
    {
        $type = static::getType($element);

        if ($this->isEquivalentTo($type)) return true;

        if ($type->isSubclassOf($this)) {
            if ($this->isGeneric() && $this->arguments != $type->getGenericArguments()) return false;
            return true;
        }

        if ($this->isInterface()) {
            if (class_implements($element, $this->name)) return true;
        }

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
        $typeName = gettype($element);
        if ($typeName == 'object') {
            $className = get_class($element);
            if (method_exists($className, 'gettype')) {
                // use method name in lower case as array key to avoid duplication
                $methodInfo = self::$methods[$className]['gettype'] ?? null;
                if (!$methodInfo) {
                    $methodInfo = new \ReflectionMethod($className, 'gettype');
                    self::$methods[$className]['gettype'] = $methodInfo;
                }
                if ($methodInfo->hasReturnType() && $methodInfo->getReturnType()->getName() == Type::class) {
                    return $element->getType();
                }
            }

            return new Type($className);
        }

        return new Type($typeName);
    }
}
