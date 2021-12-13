<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async\Tasks;

class TaskScheduler
{
    private static TaskScheduler $Scheduler;

    protected int $MaxConcurrency = 0;
    protected array $Tasks = [];

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(int $maxConcurrency = 0)
    {
        $this->MaxConcurrency = $maxConcurrency;
        register_shutdown_function([$this, 'shutdown']);
    }

    public function enqueue(Task $task): void
    {
        if (isset($this->Tasks[$task->Id])) {
            return;
        }
        $this->Tasks[$task->Id] = $task;
    }

    public function dequeue(Task $task): void
    {
        if (isset($this->Tasks[$task->Id])) {
            unset($this->Tasks[$task->Id]);
        }

        $count = 0;
        $vacancy = $this->MaxConcurrency - count($this->getScheduledTasks());
        foreach ($this->Tasks as $task) {
            if ($count > $vacancy) {
                break;
            }
            if ($task->Status == Task::Pending) {
                $task->start();
                $count++;
            }
        }
    }

    public function getScheduledTasks(): array
    {
        $tasks = [];
        foreach ($this->Tasks as $task) {
            if ($task->Status == Task::Running) {
                $tasks[] = $task;
            }
        }
        return $tasks;
    }

    public function shutdown(): void
    {
        foreach ($this->Tasks as $task) {
            $task->wait();
        }
    }

    public static function getDefaultScheduler(): TaskScheduler
    {
        if (!isset(self::$Scheduler)) {
            self::$Scheduler = new TaskScheduler();
        }

        return self::$Scheduler;
    }
}
