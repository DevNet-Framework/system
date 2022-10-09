<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Runtime;

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

    public function run(): int
    {
        if (!class_exists($this->mainClass)) {
            return 1;
        }

        if (!method_exists($this->mainClass, 'main')) {
            return 2;
        }

        $this->mainClass::main($this->args);

        Task::waitAll(TaskScheduler::getDefaultScheduler()->Tasks);
        return 0;
    }
}
