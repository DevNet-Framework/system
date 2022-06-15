<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\Exceptions\ArrayException;
use ArrayAccess;

class ArrayList implements ArrayAccess, IList
{
    use \DevNet\System\Collections\ArrayTrait;
    use \DevNet\System\Extension\ExtenderTrait;
    use \DevNet\System\Reflection\ReflectionTrait;

    public function __construct(string $valueType)
    {
        $this->Type = $this->getType()->makeGenericType(['int', $valueType]);
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

    public function toArray(): array
    {
        return $this->Array;
    }
}
