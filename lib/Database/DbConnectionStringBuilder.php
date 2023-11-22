<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use ArrayAccess;
use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;
use DevNet\System\Exceptions\TypeException;
use DevNet\System\PropertyTrait;

class DbConnectionStringBuilder implements IEnumerable, ArrayAccess
{
    use PropertyTrait;

    protected array $items = [];

    public function set_ConnectionString(string $connectionString): void
    {
        preg_match_all('%;?([^=]+)\s*=\s*([^;]+)%', $connectionString, $matches);
        if ($matches) {
            foreach ($matches[1] as $index => $key) {
                $this->items[strtolower($key)] = $matches[2][$index];
            }
        }
    }

    public function get_ConnectionString(): string
    {
        $items = [];
        foreach ($this->items as $key => $value) {
            $items[] =  $key . '=' . $value;
        }

        return implode(';', $items);
    }

    public function get_Items(): array
    {
        return $this->Items;
    }

    public function offsetSet($key, $value): void
    {
        if (!is_string($key)) {
            throw new TypeException("Illegal key type, the key must be of type string", 0, 1);
        }

        if (!is_string($value)) {
            throw new TypeException("Illegal value type, the value must be of type string", 0, 1);
        }

        $this->items[strtolower($key)] = $value;
    }

    public function offsetExists($key): bool
    {
        if (!is_string($key)) {
            throw new TypeException("Illegal key type, the key must be of type string", 0, 1);
        }

        return isset($this->items[strtolower($key)]);
    }

    public function offsetGet($key): mixed
    {
        if (!is_string($key)) {
            throw new TypeException("Illegal key type, the key must be of type string", 0, 1);
        }

        return $this->items[strtolower($key)] ?? null;
    }

    public function offsetUnset($key): void
    {
        if (!is_string($key)) {
            throw new TypeException("Illegal key type, the key must be of type string", 0, 1);
        }

        unset($this->items[strtolower($key)]);
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->items);
    }

    public function __toString()
    {
        return $this->ConnectionString;
    }
}
