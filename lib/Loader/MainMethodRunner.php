<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Loader;

use DevNet\System\Async\AsyncFunction;
use DevNet\System\Async\TaskScheduler;

class MainMethodRunner
{
    private string $MainClass;
    private array $Args;

    public function __construct($mainClass, $args)
    {
        $this->MainClass = $mainClass;
        $this->Args = $args;
    }

    public function run(): void
    {
        if (!class_exists($this->MainClass)) {
            throw new \Exception("Main class does not exist or not configured yet");
        }

        if (!method_exists($this->MainClass, 'main')) {
            throw new \Exception("Main Method does not exist or entry point not configured yet");
        }

        $mainAsync = new AsyncFunction([$this->MainClass, 'main']);
        $task = $mainAsync($this->Args);
        $task->wait();
    }
}
