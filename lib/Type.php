<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\ArrayException;
use DevNet\System\Exceptions\TypeException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class Type
{
    public readonly string $Name;
    private array $parameters = [];

    private static array $properties = [];
    private static array $methods    = [];

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

        // The remaining case is considered a class and must be in PascalCase.
        $this->Name = $name;

        if ($this->isClass()) {
            $class = new ReflectionClass($this->Name);
            foreach ($class->getAttributes(Generic::class) as $attribute) {
                $generic = $attribute->newInstance();
                foreach ($generic->getTypes() as $type) {
                    if (!is_subclass_of($type->Name, Parameter::class)) {
                        throw new TypeException("Parameter types must of type T or equivalent", 0, 1);
                    }

                    if (isset($this->parameters[$type->Name])) {
                        throw new TypeException("The generic type should not have a repeated generic parameter.", 0, 1);
                    }

                    $this->parameters[$type->Name] = $type;
                }

                // No need to look for other attributes.
                break;
            }
        }

        if (count($this->parameters) != count($arguments)) {
            throw new ArrayException("The number of generic arguments must be the same as the number of generic parameters.", 1);
        }

        $index = 0;
        foreach ($this->parameters as &$parameter) {
            $argument = $arguments[$index] ?? null;
            if (!$argument || !is_string($argument)) {
                throw new ArrayException("Type arguments must be of type array<int, string>", 0, 1);
            }
            $parameter = new Type($argument);
            $index++;
        }
    }

    public function makeGenericType(array $typeArguments): Type
    {
        return new Type($this->Name, $typeArguments);
    }

    public function getGenericArguments(): array
    {
        return $this->parameters;
    }

    public function getInterfaces(): array
    {
        $interfaces = class_implements($this->Name);
        if (!$interfaces) {
            $interfaces = [];
        }
        return $interfaces;
    }

    public function getProperty(string $property): ?ReflectionProperty
    {
        if (isset(self::$properties[$this->Name][$property]))
            return self::$properties[$this->Name][$property];

        if ($this->isClass()) {
            if (property_exists($this->Name, $property)) {
                $propertyInfo = new ReflectionProperty($this->Name, $property);
                self::$properties[$this->Name][$property] = $propertyInfo;
                return $propertyInfo;
            }
        }

        return null;
    }

    public function getMethod(string $method): ?ReflectionMethod
    {
        // use method name in lower case as array key to avoid duplication
        $method = strtolower($method);
        if (isset(self::$methods[$this->Name][$method])) return self::$methods[$this->Name][$method];

        if ($this->isClass()) {
            if (method_exists($this->Name, $method)) {
                $methodInfo = new ReflectionMethod($this->Name, $method);
                self::$methods[$this->Name][$method] = $methodInfo;
                return $methodInfo;
            }
        }

        return null;
    }

    public function isPrimitive(): bool
    {
        $types = ['boolean', 'integer', 'float', 'string'];
        return in_array($this->Name, $types) ? true : false;
    }

    public function isInterface(): bool
    {
        return interface_exists($this->Name);
    }

    public function isClass(): bool
    {
        return class_exists($this->Name);
    }

    public function isGenericType(): bool
    {
        return $this->isClass() && $this->parameters ? true : false;
    }

    public function isGenericParameter(): bool
    {
        return $this->Name == T::class || is_subclass_of($this->Name, T::class) ? true : false;
    }

    public function isSubclassOf(Type $class): bool
    {
        return is_subclass_of($this->Name, $class->Name);
    }

    public function isEquivalentTo(Type $type): bool
    {
        if ($this == $type) return true;
        if ($this->Name == 'object' && $type->isClass()) return true;
        if ($type->Name == 'object' && $this->isClass()) return true;

        return false;
    }

    public function isAssignableFrom(Type $type): bool
    {
        if ($type->isEquivalentTo($this)) return true;

        if ($type->isSubclassOf($this)) {
            if ($this->parameters != $type->getGenericArguments()) return false;
            return true;
        }

        if ($this->isInterface()) {
            return in_array($this->Name, $type->getInterfaces());
        }

        return false;
    }

    public function isAssignableTo(Type $type): bool
    {
        if ($this->isEquivalentTo($type)) return true;

        if ($this->isSubclassOf($type)) {
            if ($this->parameters != $type->getGenericArguments()) return false;
            return true;
        }

        if ($type->isInterface()) {
            return in_array($type->Name, $this->getInterfaces());
        }

        return false;
    }

    public function isTypeOf($element): bool
    {
        $type = static::getType($element);
        return $this->isAssignableFrom($type);
    }

    public function __toString(): string
    {
        $name = $this->Name;
        if ($this->isGenericType()) $name .= '<' . implode(',', $this->parameters) . '>';

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
