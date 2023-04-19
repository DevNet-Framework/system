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
    private object|array $action;

    public function __construct(callable $action)
    {
        $this->action = $action;
    }

    public function invoke(array $args = []): Task
    {
        $task = new Task(function () use ($args) {
            $fiber = new Fiber ($this->action);
            $asyncResult = call_user_func_array([$fiber, 'start'], $args);
            while (!$fiber->isTerminated()) {
                yield;
                if ($asyncResult instanceof IAwaitable) {
                    if ($asyncResult->getAwaiter()->isCompleted()) {
                        $asyncResult = $fiber->resume($asyncResult);
                    }
                } else {
                    $asyncResult = $fiber->throw(new \Exception("Async function must await an IAwaitable task!"));
                }
            }

            return $fiber->getReturn();
        });

        $task->start(new TaskScheduler());
        return $task;
    }

    public function __invoke(...$args): Task
    {
        return $this->invoke($args);
    }
}
