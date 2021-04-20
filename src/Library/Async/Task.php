<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use Closure;
use Exception;

class Task
{
    public const Created   = 0;
    public const Started   = 1;
    public const Completed = 2;
    public const Canceled  = -1;
    public const Faulted   = -2;

    private int $Id;
    private int $Status;
    private float $Delay = 0;
    private Closure $Action;
    private TaskScheduler $Scheduler;
    private TaskCancelationToken $Token;
    private Task $Next;
    private $Result = null;

    public function __construct(Closure $action, ?TaskCancelationToken $token = null)
    {
        $action = function() use($action)
        {
            return $action();
        };

        if ($token)
        {
            $this->Token = $token;
        }

        $this->Action = $action;
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

            return $next($previous);
        };
        
        $this->Next = new Task($next);
        return $this->Next;
    }

    public function execute() : void
    {
        $this->Scheduler->remove($this);

        if (isset($this->Token))
        {
            if ($this->Token->IsCanceled)
            {
                $action = $this->Token->Action ?? null;
                if ($action)
                {
                    $action($this);
                }

                $this->Status = Task::Canceled;
                return;
            }
        }

        if ($this->Status == Task::Created || $this->Status == Task::Started)
        {
            $action = $this->Action;
            $this->Result = $action();
            $this->Status = Task::Completed;
        }

        if (isset($this->Next))
        {
            $this->Next->start();
        }
    }

    public function wait() : void
    {
        $this->Status = self::Started;
        while ($this->Status === self::Started)
        {
            $this->execute();
        }
    }

    public static function run(Closure $action, ?TaskCancelationToken $token = null) : Task
    {
        $task = new Task($action, $token);
        $task->start();
        return $task;
    }

    public static function fromResult($result) : Task
    {
        return Task::run(function () use($result)
        {
            yield $result;
        });
    }

    public static function fromException(Exception $exception) : Task
    {
        return Task::run(function () use($exception)
        {
            throw $exception;
        });
    }

    public static function completedTask()
    {
        return new Task(fn() => null);
    }
}
