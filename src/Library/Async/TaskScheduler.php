<?php declare(strict_types = 1);
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
    private array $Tasks = [];

    public static function getDefaultScheduler() : TaskScheduler
    {
        if (!isset(self::$Scheduler))
        {
            self::$Scheduler = new TaskScheduler();
        }

        return self::$Scheduler;
    }

    public function add(Task $task)
    {
        $this->Tasks[$task->Id] = $task;
    }

    public function remove(Task $task) : bool
    {
        if (isset($this->Tasks[$task->Id]))
        {
            unset($this->Tasks[$task->Id]);
            return true;
        }
        
        return false;
    }

    public function execute()
    {
        while ($this->Tasks)
        {
            foreach ($this->Tasks as $task)
            {
                $task->yield();
            }
        }
    }
}
