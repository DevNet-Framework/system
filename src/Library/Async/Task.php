<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use ReflectionFunction;
use Generator;
use Closure;

class Task
{
    public const Created   = 0;
    public const Started   = 1;
    public const Completed = 2;
    public const Faulted   = -1;
    public const Canceled  = -2;

    private int $Id;
    private int $Status;
    private float $Delay = 0;
    private Generator $Action;
    private TaskScheduler $Scheduler;
    private Task $Next;
    private $Result = null;

    public function __construct(Closure $action)
    {
        $actionInfo = new ReflectionFunction($action);

        if (!$actionInfo->isGenerator())
        {
            $action = function() use($action)
            {
                yield $action();
            };
        }

        $this->Action = $action();
        $this->Id = spl_object_id ($this);
        $this->Status = Self::Created;
        $this->Scheduler = TaskScheduler::getDefaultScheduler();
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function start(TaskScheduler $taskScheduler = null) : void
    {
        if ($taskScheduler)
        {
            $this->Scheduler = $taskScheduler;
        }
        
        if ($this->Status === self::Created)
        {
            $this->Scheduler->add($this);
        }
    }

    public function delay(int $milliseconds)
    {
        $this->Delay = $milliseconds;

        if (isset($this->Scheduler))
        {
            $this->start();
        }
        return $this;
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

            $actionInfo = new ReflectionFunction($next);

            if (!$actionInfo->isGenerator())
            {
                return $next($previous);
            }

            yield from $next($previous);
        };
        
        $this->Next = new Task($next);
        return $this->Next;
    }

    public function wait() : void
    {
        if ($this->Status === self::Created || $this->Status === self::Started)
        {
            while ($this->Action->valid())
            {
                $this->Result = $this->Action->current();
                $this->Action->next();
            }
            
            $this->Status = Self::Completed;
            TaskScheduler::getDefaultScheduler()->remove($this);

            if (isset($this->Next))
            {
                $this->Next->start();
            }
        }
    }

    public function yield() : void
    {
        $this->Scheduler->remove($this);

        if ($this->Status == Task::Created)
        {
            $this->Status = Task::Started;
            $this->Result = $this->Action->current();
        }
        else
        {
            $this->Action->next();
            $this->Result = $this->Action->current();
        }
        

        if ($this->Action->valid())
        {
            $this->Scheduler->add($this);
        }
        else
        {
            $this->Status = Task::Completed;
            if (isset($this->Next))
            {
                $this->Next->start();
            }
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
