<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
