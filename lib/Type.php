<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\PropertyException;

class Type
{
    public const Boolean = 'boolean';
    public const Integer = 'integer';
    public const Float   = 'float';
    public const String  = 'string';
    public const Array   = 'array';
    public const Object  = 'object';

    private string $name;
    private array $genericArgs = [];

    public function __get(string $name)
    {
        if ($name == 'Name') {
            return $this->name;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
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

            $this->genericArgs[] = new Type($argument);
        }
    }

    public function validateArguments(...$args): int
    {
        foreach ($this->genericArgs as $index => $GenericTypeArg) {
            if (isset($args[$index])) {
                $arg = $args[$index];
                if ($GenericTypeArg->Name == gettype($arg)) {
                    continue;
                }

                if (is_object($arg)) {
                    $type = get_class($arg);
                    if ($GenericTypeArg->Name == $type) {
                        continue;
                    }
                }

                return $index + 1;
            } else {
                return ($index + 1) * -1;
            }

            $index++;
        }

        return 0;
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
        if ($this->isClass() && $this->genericArgs) {
            return true;
        }
        return false;
    }

    public function isSubclassOf(Type $class): bool
    {
        return is_subclass_of($this->name, $class->Name);
    }

    public function getGenericArguments(): array
    {
        return $this->genericArgs;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public static function typeOf($value): string
    {
        if (is_object($value)) {
            return get_class($value);
        } else {
            return gettype($value);
        }
    }

    public static function getType(string $type)
    {
        return new Type($type);
    }
}
