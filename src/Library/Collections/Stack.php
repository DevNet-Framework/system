<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

class Stack extends ArrayList
{
    public function peek()
    {
        return end($this->Array);
    }

    public function pop()
    {
        return array_pop($this->Array);
    }
}
