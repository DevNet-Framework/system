<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use Iterator;
use Countable;

class Enumerator implements Iterator, Countable
{
    private array $array = [];
    private $key;

    public function __construct(array $array = [])
    {
        $this->array = $array;
        $this->key = key($this->array);
    }

    public function rewind(): void
    {
        reset($this->array);
        $this->key = key($this->array);
    }

    public function next(): void
    {
        next($this->array);
        $this->key = key($this->array);
    }

    public function valid(): bool
    {
        return isset($this->array[$this->key]);
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->array[$this->key] ?? null;
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->key ?? null;
    }

    public function count(): int
    {
        return count($this->array);
    }

    public function toArray(): array
    {
        return $this->array;
    }
}
