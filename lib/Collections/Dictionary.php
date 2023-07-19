<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\Exceptions\ArgumentException;
use DevNet\System\Generic;
use DevNet\System\MethodTrait;
use DevNet\System\Type;

class K extends \DevNet\System\Parameter {}
class V extends \DevNet\System\Parameter {}

#[Generic(K::class, V::class)]
class Dictionary extends AbstractArray implements IDictionary
{
    use MethodTrait;

    public function __construct(string $keyType, string $valueType)
    {
        $keyType = strtolower($keyType);
        if ($keyType != 'int' && $keyType != 'integer' &&  $keyType != 'string') {
            throw new ArgumentException(self::class . "::__construct(): Key type must be defined as integer or string", 0, 1);
        }

        $this->setGenericArguments($keyType, $valueType);
    }

    public function add($key, $value): void
    {
        $genericArgs = $this->getType()->getGenericArguments();

        if (!Type::getType($key)->isEquivalentTo($genericArgs[0])) {
            throw new ArgumentException(self::class . "::add(): The argument #1, must be of type {$genericArgs[0]}", 0, 1);
        }

        if (!Type::getType($value)->isEquivalentTo($genericArgs[1])) {
            throw new ArgumentException(self::class . "::add(): The argument #2, must be of type {$genericArgs[1]}", 0, 1);
        }

        $this->array[$key] = $value;
    }

    public function contains($key): bool
    {
        $genericArgs = $this->getType()->getGenericArguments();

        if (!Type::getType($key)->isEquivalentTo($genericArgs[0])) {
            throw new ArgumentException(self::class . "::contains(): The argument #1, must be of type {$genericArgs[0]}", 0, 1);
        }

        return isset($this->array[$key]) ? true : false;
    }

    public function remove($key): void
    {
        $genericArgs = $this->getType()->getGenericArguments();

        if (!Type::getType($key)->isEquivalentTo($genericArgs[0])) {
            throw new ArgumentException(self::class . "::remove(): The argument #1, must be of type {$genericArgs[0]}", 0, 1);
        }

        if (isset($this->array[$key])) unset($this->array[$key]);
    }

    public function getValue($key)
    {
        $genericArgs = $this->getType()->getGenericArguments();

        if (!Type::getType($key)->isEquivalentTo($genericArgs[0])) {
            throw new ArgumentException(self::class . "::getValue(): The argument #1, must be of type {$genericArgs[0]}", 0, 1);
        }

        return $this->array[$key] ?? null;
    }
}
