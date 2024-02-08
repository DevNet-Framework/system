<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DevNet\System\Collections\Enumerator;
use IteratorAggregate;
use Traversable;

class TraceListenerCollection implements IteratorAggregate
{
    private array $listeners = [];

    public function add(TraceListener $listener): void
    {
        $this->listeners[get_class($listener)] = $listener;
    }

    public function remove(TraceListener $listener): bool
    {
        if (isset($this->listeners[get_class($listener)])) {
            unset($this->listeners[get_class($listener)]);
            return true;
        }

        return false;
    }

    public function getIterator(): Traversable
    {
        return new Enumerator($this->listeners);
    }
}
