<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\Exceptions\TypeException;
use DevNet\System\ObjectTrait;

class Queue implements IEnumerable
{
    use ObjectTrait;

    private array $array = [];

    public function __construct(string $valueType)
    {
        $this->setGenericType([$valueType]);
    }

    public function enqueue($value): void
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (!$genericArgs[0]->isTypeOf($value)) {
            $className = get_class($this);
            throw new TypeException("The value passed to {$className} must be of the type {$genericArgs[0]}", 0, 1);
        }

        $this->array[] = $value;
    }

    public function dequeue()
    {
        return array_shift($this->array);
    }

    public function peek()
    {
        return reset($this->array);
    }

    public function contains($item): bool
    {
        return in_array($item, $this->array);
    }

    public function remove($item): void
    {
        if (isset($this->array[$item])) {
            unset($this->array[$item]);
        }
    }

    public function clear(): void
    {
        $this->array = [];
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->array);
    }

    public function toArray(): array
    {
        return $this->array;
    }
}
