<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use Fiber;

class AsyncFunction
{
    private Fiber $fiber;

    public function __construct(callable $action)
    {
        $this->fiber = new Fiber($action);
    }

    public function invokeAsync(array $args = []): Task
    {
        $task = Task::run(function () use ($args) {
            $asyncResult = $this->fiber->start($args);
            while (!$this->fiber->isTerminated()) {
                yield;
                if ($asyncResult instanceof IAwaitable) {
                    if ($asyncResult->getAwaiter()->isCompleted()) {
                        $asyncResult = $this->fiber->resume($asyncResult->getAwaiter()->getResult());
                    }
                } else {
                    $asyncResult = $this->fiber->throw(new \Exception("Async function must await an IAwaitable task!"));
                }
            }

            return $this->fiber->getReturn();
        });

        $task->start(new TaskScheduler());
        return $task;
    }

    public function __invoke(...$args): Task
    {
        return $this->invokeAsync($args);
    }
}
