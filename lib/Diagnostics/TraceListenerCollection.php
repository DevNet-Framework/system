<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DevNet\System\Collections\Enumerator;
use IteratorAggregate;
use Traversable;

class TraceListenerCollection implements IteratorAggregate
{
    private array $Listeners = [];

    public function add(TraceListener $listener): void
    {
        $this->Listeners[get_class($listener)] = $listener;
    }

    public function remove(TraceListener $listener): bool
    {
        if (isset($this->Listeners[get_class($listener)])) {
            unset($this->Listeners[get_class($listener)]);
            return true;
        }

        return false;
    }

    public function getIterator(): Traversable
    {
        return new Enumerator($this->Listeners);
    }
}
