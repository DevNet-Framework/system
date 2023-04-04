<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Runtime;

use DevNet\System\Async\Task;
use DevNet\System\Async\TaskScheduler;

class MainMethodRunner
{
    private string $mainClass;

    public function __construct(string $mainClass)
    {
        $this->mainClass = $mainClass;
    }

    public function run(array $args = []): int
    {
        if (!class_exists($this->mainClass)) {
            return 1;
        }

        if (!method_exists($this->mainClass, 'main')) {
            return 2;
        }

        $this->mainClass::main($args);

        Task::waitAll(TaskScheduler::getDefaultScheduler()->Tasks);
        return 0;
    }
}
