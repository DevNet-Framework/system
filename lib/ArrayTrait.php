<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Exceptions\TypeException;
use DevNet\System\Type;

trait ArrayTrait
{
    use ObjectTrait;

    protected array $array = [];

    /** 
     * set to the array an element with the provided key and value.
     */
    public function offsetSet($key, $value): void
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (!Type::getType($value)->isEquivalentTo($genericArgs[1])) {
            throw new TypeException("Illegal value type, the value must be of type {$genericArgs[1]}", 0, 1);
        }

        if ($key != null) {
            if (!Type::getType($key)->isEquivalentTo($genericArgs[0])) {
                throw new TypeException("Illegal key type, the key must be of type {$genericArgs[0]}", 0, 1);
            }
            $this->array[$key] = $value;
        } else {
            if ($genericArgs[0]->Name != 'integer') {
                throw new TypeException("undefined key, expecting a key of type {$genericArgs[0]}", 0, 1);
            }
            $this->array[] = $value;
        }
    }

    /**
     * check if the array contains an element with the specified key.
     */
    public function offsetExists($key): bool
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (!Type::getType($key)->isEquivalentTo($genericArgs[0])) {
            throw new TypeException("Illegal key type, the key must be of type {$genericArgs[0]}", 0, 1);
        }
        return isset($this->array[$key]);
    }

    /**
     * Gets the element associated with the specified key.
     */
    public function offsetGet($key)
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (!Type::getType($key)->isEquivalentTo($genericArgs[0])) {
            throw new TypeException("Illegal key type, the key must be of type {$genericArgs[0]}", 0, 1);
        }
        return $this->array[$key] ?? null;
    }

    /**
     * unset an element associated with the specified key.
     */
    public function offsetUnset($key): void
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if  (!Type::getType($key)->isEquivalentTo($genericArgs[0])) {
            throw new TypeException("Illegal key type, the key must be of type {$genericArgs[0]}", 0, 1);
        }
        unset($this->array[$key]);
    }

    /** 
     * reverse the order of the array items.
     */
    public function reverse(): self
    {
        array_reverse($this->array);
        return $this;
    }

    /**
     * Removes all elements.
     */
    public function clear(): void
    {
        $this->array = [];
    }

    /**
     * Copies the elements to new array.
     */
    public function toArray(): array
    {
        return $this->array;
    }

    /**
     * return the size of the array.
     */
    public function getLength(): int
    {
        return count($this->array);
    }

    /**
     * return Iterable collection of values.
     */
    public function getIterator(): Enumerator
    {
        return new Enumerator($this->array);
    }
}
