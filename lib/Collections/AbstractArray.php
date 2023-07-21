<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Exceptions\TypeException;
use DevNet\System\Tweak;
use DevNet\System\Type;
use ArrayAccess;

abstract class AbstractArray implements ArrayAccess
{
    use Tweak;

    protected array $array = [];

    /** 
     * Sets a specified key and value to the array.
     */
    public function offsetSet($key, $value): void
    {
        $genericArgs = $this->getType()->getGenericArguments();
        $valueType = end($genericArgs);

        if (!Type::getType($value)->isAssignableTo($valueType)) {
            throw new TypeException("Illegal value type, the value must be of type {$valueType}", 0, 1);
        }

        if (count($genericArgs) == 1) {
            if (!is_int($key) && !is_null($key)) {
                throw new TypeException("Illegal key type, the key must be of type integer", 0, 1);
            }
        } else {
            $keyType = reset($genericArgs);
            if ((is_null($key) && $keyType->Name != 'integer') || (!is_null($key) && Type::getType($key) != $keyType)) {
                throw new TypeException("Illegal key type, the key must be of type {$keyType}", 0, 1);
            }
        }

        if (is_null($key)) {
            $this->array[] = $value;
        } else {
            $this->array[$key] = $value;
        }
    }

    /**
     * Checks if the array contains a specified key.
     */
    public function offsetExists($key): bool
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (count($genericArgs) == 1) {
            if (!is_int($key)) {
                throw new TypeException("Illegal key type, the key must be of type integer", 0, 1);
            }
        } else {
            $keyType = reset($genericArgs);
            if (!Type::getType($key)->isEquivalentTo($keyType)) {
                throw new TypeException("Illegal key type, the key must be of type {$keyType}", 0, 1);
            }
        }
        return isset($this->array[$key]);
    }

    /**
     * Gets the value associated with the specified key.
     */
    public function offsetGet($key): mixed
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (count($genericArgs) == 1) {
            if (!is_int($key)) {
                throw new TypeException("Illegal key type, the key must be of type integer", 0, 1);
            }
        } else {
            $keyType = reset($genericArgs);
            if (!Type::getType($key)->isEquivalentTo($keyType)) {
                throw new TypeException("Illegal key type, the key must be of type {$keyType}", 0, 1);
            }
        }
        return $this->array[$key] ?? null;
    }

    /**
     * Unsets the value with the specified key from the array.
     */
    public function offsetUnset($key): void
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (count($genericArgs) == 1) {
            if (!is_int($key)) {
                throw new TypeException("Illegal key type, the key must be of type integer", 0, 1);
            }
        } else {
            $keyType = reset($genericArgs);
            if (!Type::getType($key)->isEquivalentTo($keyType)) {
                throw new TypeException("Illegal key type, the key must be of type {$keyType}", 0, 1);
            }
        }
        unset($this->array[$key]);
    }

    /** 
     * Reverses the order of the elements in the array.
     */
    public function reverse(): static
    {
        array_reverse($this->array);
        return $this;
    }

    /**
     * Removes all the elements from the array.
     */
    public function clear(): void
    {
        $this->array = [];
    }

    /**
     * Copies all the elements to a new array.
     */
    public function toArray(): array
    {
        return $this->array;
    }

    /**
     * Returns the size of the array.
     */
    public function getLength(): int
    {
        return count($this->array);
    }

    /**
     * Returns an enumerator that iterates through a collection.
     */
    public function getIterator(): Enumerator
    {
        return new Enumerator($this->array);
    }
}
