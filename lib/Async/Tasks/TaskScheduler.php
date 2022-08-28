<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async\Tasks;

use DevNet\System\ObjectTrait;

class TaskScheduler
{
    use ObjectTrait;

    private static TaskScheduler $scheduler;

    protected int $maxConcurrency = 0;
    protected array $tasks = [];

    public function __construct(int $maxConcurrency = 0)
    {
        $this->maxConcurrency = $maxConcurrency;
    }

    public function get_MaxConcurrency(): int
    {
        return $this->maxConcurrency;
    }

    public function get_Tasks(): array
    {
        return $this->tasks;
    }

    public function enqueue(Task $task): void
    {
        if (isset($this->tasks[$task->Id])) {
            return;
        }
        $this->tasks[$task->Id] = $task;
    }

    public function dequeue(Task $task): void
    {
        if (isset($this->tasks[$task->Id])) {
            unset($this->tasks[$task->Id]);
        }

        $count = 0;
        $vacancy = $this->maxConcurrency - count($this->getScheduledTasks());
        foreach ($this->tasks as $task) {
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
        foreach ($this->tasks as $task) {
            if ($task->Status == Task::Running) {
                $tasks[] = $task;
            }
        }
        return $tasks;
    }

    public static function getDefaultScheduler(): TaskScheduler
    {
        if (!isset(self::$scheduler)) {
            self::$scheduler = new TaskScheduler();
        }

        return self::$scheduler;
    }
}
