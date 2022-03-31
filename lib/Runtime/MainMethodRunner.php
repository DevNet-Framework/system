<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Runtime;

use DevNet\System\Async\AsyncFunction;
use DevNet\System\Async\Tasks\Task;
use DevNet\System\Async\Tasks\TaskScheduler;

class MainMethodRunner
{
    private string $mainClass;
    private array $args;

    public function __construct($mainClass, $args)
    {
        $this->mainClass = $mainClass;
        $this->args = $args;
    }

    public function run(): void
    {
        if (!class_exists($this->mainClass)) {
            throw new \Exception("Main class does not exist or not configured yet");
        }

        if (!method_exists($this->mainClass, 'main')) {
            throw new \Exception("Main Method does not exist or entry point not configured yet");
        }

        $mainAsync = new AsyncFunction([$this->mainClass, 'main']);
        $task = $mainAsync($this->args);

        $task->wait();
        Task::waitAll(TaskScheduler::getDefaultScheduler()->Tasks);
    }
}
