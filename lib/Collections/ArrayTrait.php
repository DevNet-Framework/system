<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\Type;
use DevNet\System\Exceptions\TypeException;

trait ArrayTrait
{
    protected array $Array = [];

    abstract public function getType(): Type;

    /** 
     * set to the array an element with the provided key and value.
     */
    public function offsetSet($key, $value): void
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (!$genericArgs[1]->isOfType($value)) {
            $className = get_class($this);
            throw new TypeException("The value passed to {$className} must be of the type {$genericArgs[1]}");
        }

        if ($key != null) {
            if (!$genericArgs[0]->isOfType($key)) {
                $className = get_class($this);
                throw new TypeException("The key passed to {$className} must be of the type {$genericArgs[0]}");
            }
            $this->Array[$key] = $value;
        } else {
            if ($genericArgs[0]->Name != 'integer') {
                throw new TypeException("Couldn't auto increment a Key of type {$genericArgs[0]}");
            }
            $this->Array[] = $value;
        }
    }

    /**
     * check if the array contains an element with the specified key
     */
    public function offsetExists($key): bool
    {
        return isset($this->Array[$key]);
    }

    /**
     * Gets the element associated with the specified key
     */
    public function offsetGet($key)
    {
        return $this->Array[$key] ?? null;
    }

    /**
     * unset an element associated with the specified key
     */
    public function offsetUnset($key): void
    {
        unset($this->Array[$key]);
    }

    /**
     * return the size of the array
     */
    public function getLength(): int
    {
        return count($this->Array);
    }

    /** 
     * reverse the order of the array items
     */
    public function reverse(): ArrayList
    {
        array_reverse($this->Array);
        return $this;
    }

    /**
     * return Iterable collection of values
     */
    public function getIterator(): Enumerator
    {
        return new Enumerator($this->Array);
    }
}
