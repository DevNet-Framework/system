<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\PropertyTrait;

class TaskScheduler
{
    use PropertyTrait;

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
            if ($task->Status == TaskStatus::Pending) {
                $task->start();
                $count++;
            }
        }
    }

    public function getScheduledTasks(): array
    {
        $tasks = [];
        foreach ($this->tasks as $task) {
            if ($task->Status == TaskStatus::Running) {
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
