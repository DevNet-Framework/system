<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\ArrayTrait;
use DevNet\System\Exceptions\ArgumentException;
use DevNet\System\Exceptions\TypeException;
use DevNet\System\MethodTrait;
use DevNet\System\Type;
use ArrayAccess;

class ArrayList implements ArrayAccess, IList
{
    use ArrayTrait;
    use MethodTrait;

    public function __construct(string $valueType)
    {
        $this->setGenericType(['int', $valueType]);
    }

    public function addRange(array $array): void
    {
        try {
            foreach ($array as $value) {
                $this->offsetSet(null, $value);
            }
        } catch (TypeException $exception) {
            $genericArgs = $this->getType()->getGenericArguments();
            throw new ArgumentException(self::class . "::addRange(): The argument #1, must be of type array<{$genericArgs[1]}>", 0, 1);
        }
    }

    public function add($item): void
    {
        try {
            $this->offsetSet(null, $item);
        } catch (TypeException $exception) {
            $genericArgs = $this->getType()->getGenericArguments();
            throw new ArgumentException(self::class . "::add(): The argument #1, must be of type {$genericArgs[1]}", 0, 1);
        }
    }

    public function contains($item): bool
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (!Type::getType($item)->isEquivalentTo($genericArgs[1])) {
            throw new ArgumentException(self::class . "::contains(): The argument #1, must be of type {$genericArgs[1]}", 0, 1);
        }

        foreach ($this->getIterator() as $value) {
            if ($value == $item) {
                return true;
            }
        }

        return false;
    }

    public function remove($item): void
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (!Type::getType($item)->isEquivalentTo($genericArgs[1])) {
            throw new ArgumentException(self::class . "::remove(): The argument #1, must be of type {$genericArgs[1]}", 0, 1);
        }

        foreach ($this->getIterator() as $key => $value) {
            if ($item == $value) {
                $this->offsetUnset($key);
                break;
            }
        }
    }

    public function removeAt(int $index): void
    {
        $this->offsetUnset($index);
    }
}
