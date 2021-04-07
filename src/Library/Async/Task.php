<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DateTime;
use Closure;
use DevNet\System\Exceptions\PropertyException;

class Task
{
    public const Created   = 0;
    public const Started   = 1;
    public const Completed = 2;
    public const Faulted   = -1;
    public const Canceled  = -2;

    private int $Id;
    private int $Status;
    private Closure $Action;
    private TaskScheduler $Scheduler;
    private Task $Next;
    private $Result = null;

    public function __construct(Closure $action)
    {
        $this->Action = $action;
        $this->Id = spl_object_id ($this);
        $this->Status = Self::Created;
        $this->Scheduler = TaskScheduler::getDefaultScheduler();
    }

    public function __get(string $name)
    {
        switch ($name)
        {
            case 'Action':
            case 'Next':
                throw new PropertyException("access to private property {$this}::{$name}");
                break;
            default:
                return $this->$name;
                break;
        }
    }

    public function start(TaskScheduler $taskScheduler = null) : void
    {
        if ($taskScheduler)
        {
            $this->Scheduler = $taskScheduler;
        }
        
        if ($this->Status === self::Created)
        {
            $this->Status = Self::Started;
            $this->Scheduler->add($this);
        }
    }

    public function then(Closure $next) : Task
    {
        $previous = $this;
        $next = function () use ($previous, $next)
        {
            if ($previous->Status !== self::Completed)
            {
                $previous->wait();
            }

            return $next($previous);
        };
        
        $this->Next = new Task($next);
        return $this->Next;
    }

    public function wait() : void
    {
        if ($this->Status === self::Created || $this->Status === self::Started)
        {
            $action = $this->Action;
            $this->Result = $action(null);
            $this->Status = Self::Completed;
            TaskScheduler::getDefaultScheduler()->remove($this);
        }

        if (isset($this->Next))
        {
            $this->Next->start();
        }
    }

    public static function run(Closure $action) : Task
    {
        $task = new Task($action);
        $task->start();
        return $task;
    }

    public static function completedTask()
    {
        return new Task(fn() => null);
    }
}
