<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Collections;

use Artister\System\Type;

class Stack extends ArrayBase
{
    private string $ValueType;

    public function __construct(string $valueType)
    {
        $this->ValueType = $valueType;
    }

    public function add($value) : void
    {
        $this->offsetSet(null, $value);
    }

    public function addRange(array $items)
    {
        $this->Array = array_merge($this->Array, $items);
    }

    public function peek()
    {
        return end($this->Array);
    }

    public function pop()
    {
        return array_pop($this->Array);
    }

    public function clear()
    {
        $this->Array = [];
    }

    public function getKeyType() : string
    {
        return Type::Integer;
    }

    public function getValueType() : string
    {
        return $this->ValueType;
    }

    public function toArray() : array
    {
        return $this->Array;
    }
}