<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Parsing;

class Stack
{
    private array $stack = [];

    public function push($item)
    {
        $this->stack[] = $item;
    }

    public function addRange(array $items): void
    {
        $this->stack = array_merge($this->stack, $items);
    }

    public function peek()
    {
        return end($this->stack);
    }

    public function pop()
    {
        return array_pop($this->stack);
    }

    public function count(): int
    {
        return count($this->stack);
    }

    public function clear(): void
    {
        $this->stack = [];
    }
}
