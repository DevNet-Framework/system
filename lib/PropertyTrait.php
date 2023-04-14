<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\PropertyException;
use DevNet\System\Exceptions\TypeException;
use ReflectionProperty;

trait PropertyTrait
{
    public function &__get(string $property)
    {
        $accessor = 'get_' . $property;
        if (method_exists($this, $accessor)) {
            $name = substr(strrchr($accessor, '_'), 1);
            if ($name == $property) {
                $value = $this->$accessor();
                return $value;
            }
        }

        if (method_exists($this, $property)) {
            return [$this, $property];
        }

        if (property_exists($this, $property)) {
            $modifier = 'private';
            $propertyInfo = new ReflectionProperty($this, $property);
            if ($propertyInfo->isProtected()) {
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
            $propertyInfo = new ReflectionProperty($this, $property);
            if ($propertyInfo->isProtected()) {
                $modifier = 'protected';
            }

            throw new PropertyException("Cannot access {$modifier} property " . static::class . "::{$property}", 0, 1);
        }

        throw new PropertyException("Cannot access undefined property " . static::class . "::{$property}", 0, 1);
    }
}
