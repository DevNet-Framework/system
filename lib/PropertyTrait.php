<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\PropertyException;
use DevNet\System\Exceptions\TypeException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @deprecated This feature is no longer supported and will be removed in the future, use property hooks instead.
 */
trait PropertyTrait
{
    public function &__get(string $name)
    {
        if (method_exists($this, 'get_' . $name)) {
            $method = new ReflectionMethod($this, 'get_' . $name);
            $accessor = $method->getName();
            if ($accessor == 'get_' . $name) {
                $value = $this->$accessor();
                return $value;
            }
        }

        if (property_exists($this, $name)) {
            $modifier = 'private';
            $property = new ReflectionProperty($this, $name);
            if ($property->isProtected()) {
                $modifier = 'protected';
            }

            throw new PropertyException("Cannot access {$modifier} property " . static::class . "::{$name}", 0, 1);
        }

        throw new PropertyException("Cannot access undefined property " . static::class . "::{$name}", 0, 1);
    }

    public function __set(string $name, $value): void
    {
        if (method_exists($this, 'set_' . $name)) {
            $method = new ReflectionMethod($this, 'set_' . $name);
            $accessor = $method->getName();
            if ($accessor == 'set_' . $name) {
                try {
                    $this->$accessor($value);
                    return;
                } catch (\TypeError $error) {
                    $type = Type::getType($value);
                    throw new TypeException("Cannot assign a value of type '{$type}' to property " . static::class . "::{$name}", 0, 1);
                }
            }
        }

        if (method_exists($this, 'get_' . $name)) {
            throw new PropertyException("Cannot assign a value to read only property " . static::class . "::{$name}", 0, 1);
        }

        if (property_exists($this, $name)) {
            $modifier = 'private';
            $property = new ReflectionProperty($this, $name);
            if ($property->isProtected()) {
                $modifier = 'protected';
            }

            throw new PropertyException("Cannot access {$modifier} property " . static::class . "::{$name}", 0, 1);
        }

        throw new PropertyException("Cannot access undefined property " . static::class . "::{$name}", 0, 1);
    }
}
