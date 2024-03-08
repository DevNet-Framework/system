<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\PropertyTrait;
use Closure;

class TaskAwaiter implements IAwaiter
{
    use PropertyTrait;

    private Task $task;
    private ?Closure $onCompleted = null;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function get_OnCompleted(): ?Closure
    {
        return $this->onCompleted;
    }

    public function isCompleted(): bool
    {
        return $this->task->IsCompleted;
    }

    public function getResult(): mixed
    {
        return $this->task->Result;
    }

    public function onCompleted(Closure $continuation): void
    {
        $this->task->then($continuation);
    }
}
