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

class Dictionary implements ArrayAccess, IDictionary
{
    use \DevNet\System\Collections\ArrayTrait;
    use \DevNet\System\Extension\ExtenderTrait;

    public function __construct(string $valueType)
    {
        $this->genericType = new Type(self::class, ['string', $valueType]);
    }

    public function add($key, $value): void
    {
        $this->offsetSet($key, $value);
    }

    public function contains($key): bool
    {
        return isset($this->Array[$key]) ? true : false;
    }

    public function remove($key): void
    {
        if (isset($this->Array[$key])) {
            unset($this->Array[$key]);
        }
    }

    public function getValue($key)
    {
        return $this->Array[$key] ?? null;
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
