<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\Runtime\LauncherProperties;

class TaskAwaiter
{
    private Task $Task;

    public function __construct(Task $task)
    {
        $this->Task = $task;
    }

    public function getResult()
    {
        return $this->Task->Result;
    }

    function isCompleted(): bool
    {
        return $this->Task->IsCompleted;
    }
}
