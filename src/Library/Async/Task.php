<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use Closure;

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
    private ?TaskCancelationToken $Token;
    private ?Task $Next = null;
    private $Parameter;
    private $Result = null;

    public function __construct(Closure $action, ?TaskCancelationToken $token = null, $parameter = null)
    {        
        $this->Id        = spl_object_id ($this);
        $this->Action    = $action;
        $this->Token     = $token;
        $this->Parameter = $parameter;
        $this->Status    = Self::Created;
        $this->Scheduler = TaskScheduler::getDefaultScheduler();
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function start(TaskScheduler $taskScheduler = null) : void
    {
        if ($this->Parameter instanceof Task)
        {
            if ($this->Parameter->Status != self::Completed)
            {
                return;
            }
        }

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
        $this->Next = new Task($next, null, $this);
        return $this->Next;
    }

    public function execute() : void
    {
        if ($this->Parameter instanceof Task)
        {
            if ($this->Parameter->Status != self::Completed)
            {
                return;
            }
        }

        $this->Scheduler->remove($this);

        if ($this->Token)
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
            $this->Result = $action($this->Parameter);
            $this->Status = Task::Completed;
        }

        if ($this->Next)
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
            return $result;
        });
    }

    public static function fromException(string $messsage, int $code = 0) : Task
    {
        return Task::run(function() use($messsage, $code)
        {
            throw new TaskException($messsage, $code);
        });
    }

    public static function completedTask()
    {
        return new Task(fn() => null);
    }
}
