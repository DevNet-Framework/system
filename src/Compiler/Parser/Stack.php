<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Compiler\Parser;

class Stack
{
    private array $stack = [];

    public function push($item)
    {
        $this->stack[] = $item;
    }

    public function addRange(array $items)
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

    public function count()
    {
        return count($this->stack);
    }

    public function clear()
    {
        $this->stack = [];
    }
}