<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use Closure;
use DevNet\System\Action;
use DevNet\System\Async\Tasks\Task;

class AsyncFunction extends Action
{
    public function invokeArgs(array $args = []): Task
    {
        $action = $this->MethodInfo;

        $task = new Task(function () use ($action, $args) {
            $result = yield $action->invokeArgs($args);
            return $result;
        });

        $task->start();
        return $task;
    }
}
