<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
