<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use ArrayAccess;
use DevNet\System\Type;
use DevNet\System\Exceptions\ArrayException;

class ArrayList implements ArrayAccess, IList
{
    use \DevNet\System\Collections\ArrayTrait;
    use \DevNet\System\Extension\ExtenderTrait;

    private Type $genericType;

    public function __construct(string $valueType)
    {
        $this->genericType = new Type(self::class, Type::Integer, $valueType);
    }

    public function add($value): void
    {
        $this->offsetSet(null, $value);
    }

    public function contains($item): bool
    {
        return in_array($item, $this->Array);
    }

    public function addRange(array $array)
    {
        foreach ($array as $key => $value) {
            if (gettype($key) != "integer") {
                throw ArrayException::invalidValueType("integer");
            }

            $this->offsetSet(null, $value);
        }
    }

    public function remove($item): void
    {
        if (isset($this->Array[$item])) {
            unset($this->Array[$item]);
        }
    }

    public function clear(): void
    {
        $this->Array = [];
    }

    public function getType(): Type
    {
        return $this->genericType;
    }

    public function toArray(): array
    {
        return $this->Array;
    }
}
