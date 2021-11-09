<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

class TaskScheduler
{
    private static TaskScheduler $Scheduler;

    private int $MaxConcurrency;
    private array $Tasks = [];

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(int $maxConcurrency = 16)
    {
        $this->MaxConcurrency = $maxConcurrency;
        register_shutdown_function([$this, 'onShutdown']);
    }

    public function add(Task $task): void
    {
        if (isset($this->Tasks[$task->Id])) {
            return;
        }

        $this->Tasks[$task->Id] = $task;
    }

    public function remove(Task $task): void
    {
        if (isset($this->Tasks[$task->Id])) {
            unset($this->Tasks[$task->Id]);
        }

        $tasks   = $this->getScheduledTasks();
        $vacancy = $this->MaxConcurrency - count($this->getActiveTasks());

        $count = 1;
        foreach ($tasks as $task) {
            $task->start();
            $count++;
            if ($count > $vacancy) {
                break;
            }
        }
    }

    public function getActiveTasks(): array
    {
        $activeTasks = [];
        foreach ($this->Tasks as $task) {
            if ($task->Awaiter->Process->isRunning()) {
                $activeTasks[] = $task;
            }
        }

        return $activeTasks;
    }

    public function getScheduledTasks(): array
    {
        $scheduledTasks = [];
        foreach ($this->Tasks as $task) {
            if ($task->Status == Task::Created) {
                $scheduledTasks[] = $task;
            }
        }

        return $scheduledTasks;
    }

    public function wait()
    {
        while ($this->Tasks) {
            foreach ($this->Tasks as $task) {
                $task->wait();
            }
        }
    }

    public function onShutdown(): void
    {
        foreach ($this->Tasks as $task) {
            $task->Awaiter->Stop();
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
