<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\Exceptions\ArgumentException;
use DevNet\System\Exceptions\TypeException;
use DevNet\System\Generic;
use DevNet\System\Type;

class T extends \DevNet\System\Parameter {}

#[Generic(T::class)]
class ArrayList extends AbstractArray implements IList
{
    public function __construct(string $valueType)
    {
        $this->setGenericArguments($valueType);
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

    public function add($element): void
    {
        try {
            $this->offsetSet(null, $element);
        } catch (TypeException $exception) {
            $genericArgs = $this->getType()->getGenericArguments();
            throw new ArgumentException(self::class . "::add(): The argument #1, must be of type {$genericArgs[1]}", 0, 1);
        }
    }

    public function contains(mixed $element): bool
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (!Type::getType($element)->isEquivalentTo($genericArgs[1])) {
            throw new ArgumentException(self::class . "::contains(): The argument #1, must be of type {$genericArgs[1]}", 0, 1);
        }

        foreach ($this->getIterator() as $value) {
            if ($value == $element) {
                return true;
            }
        }

        return false;
    }

    public function remove(mixed $element): void
    {
        $genericArgs = $this->getType()->getGenericArguments();
        if (!Type::getType($element)->isEquivalentTo($genericArgs[1])) {
            throw new ArgumentException(self::class . "::remove(): The argument #1, must be of type {$genericArgs[1]}", 0, 1);
        }

        foreach ($this->getIterator() as $key => $value) {
            if ($element == $value) {
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
