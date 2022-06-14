<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Reflection;

use DevNet\System\Exceptions\PropertyException;

class Type
{
    private string $name;
    private array $arguments = [];

    public function __get(string $name)
    {
        if ($name == 'Name') {
            return $this->name;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(string $name, array $arguments = [])
    {
        switch (strtolower($name)) {
            case 'boolean':
            case 'bool':
                $this->name = 'boolean';
                break;
            case 'integer':
            case 'int':
                $this->name = 'integer';
                break;
            case 'float':
            case 'double':
                $this->name = 'float';
                break;
            case 'string':
                $this->name = 'string';
                break;
            case 'array':
                $this->name = 'array';
                break;
            case 'object':
                $this->name = 'object';
                break;
            default:
                $this->name = $name;
                break;
        }

        foreach ($arguments as $argument) {
            if (!is_string($argument)) {
                $type = gettype($argument);
                throw new \Exception("Generic parameter type name must be of type string, {$type} was given");
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

    public function isPrimitive(): bool
    {
        $types = ['boolean', 'integer', 'float', 'string'];
        if (in_array($this->name, $types)) {
            return true;
        }

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
        if ($this->isClass() && $this->arguments) {
            return true;
        }
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

        if ($this->isEquivalentTo($type)) {
            return true;
        }

        if ($type->isSubclassOf($this)) {
            return true;
        }

        if ($this->name == 'object' && $type->isClass()) {
            return true;
        }

        return false;
    }

    public function __toString(): string
    {
        $typeName = $this->name;
        if ($this->isGeneric()) {
            $typeName .= '<' . implode(', ', $this->arguments) . '>';
        }

        return $typeName;
    }

    public static function getType($element): Type
    {
        $typeName = getType($element);
        if ($typeName == 'object') {
            $typeName = get_class($element);
            if (method_exists($element, 'getType')) {
                return $element->getType();
            }
        }

        return new Type($typeName);
    }
}
