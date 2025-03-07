<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use ArrayAccess;
use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;
use DevNet\System\Exceptions\TypeException;

class DbConnectionStringBuilder implements IEnumerable, ArrayAccess
{
    protected array $items = [];

    public array $Items { get => $this->items; }
    public string $ConnectionString {
        get {
            $items = [];
            foreach ($this->items as $key => $value) {
                $items[] =  $key . '=' . $value;
            }

            return implode(';', $items);
        }
        set {
            preg_match_all('%;?([^=]+)\s*=\s*([^;]+)%', $value, $matches);
            if ($matches) {
                foreach ($matches[1] as $index => $key) {
                    $this->items[strtolower($key)] = $matches[2][$index];
                }
            }
        }
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

    public function __toString(): string
    {
        return $this->ConnectionString;
    }
}
