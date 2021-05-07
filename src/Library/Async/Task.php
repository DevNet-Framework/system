<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use Opis\Closure\SerializableClosure;
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
    private TaskAwaiter $Awaiter;
    private TaskScheduler $Scheduler;
    private $Action = null;
    private $Result = null;

    public function __construct(Closure $action = null, ?TaskCancelationToken $token = null)
    {
        $this->Id        = spl_object_id($this);
        $this->Status    = Self::Completed;
        $this->Awaiter   = new TaskAwaiter();
        $this->Scheduler = TaskScheduler::getDefaultScheduler();

        if ($action)
        {
            $this->Action = new SerializableClosure($action);
            $this->Status = Self::Created;
        }

        if ($token)
        {
            $token->Awaiters->add($this->TaskAwaiter);
        }
    }

    public function __get(string $name)
    {
        if ($name == 'Status')
        {
            if ($this->Status == self::Started)
            {
                if ($this->Awaiter->IsComplited)
                {
                    $this->wait();
                }
            }
        }

        if ($name == 'Result')
        {
            if ($this->Status == self::Created || $this->Status == self::Started)
            {
                $this->wait();
            }
        }

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
            $serialized = serialize($this->Action);
            $this->Awaiter->Process->start();
            $this->Awaiter->Process->setInput($serialized);
            $this->Status = self::Started;
        }
    }

    public function then(Closure $next, ?TaskCancelationToken $token = null)
    {
        $this->wait();
        $precedent = $this;
        $next = function() use($next, $precedent)
        {
            return $next($precedent);
        };

        return Task::run($next, $token);
    }

    public function wait() : void
    {
        if ($this->Status == self::Created || $this->Status == self::Started)
        {
            if ($this->Status == self::Created)
            {
                $action = $this->Action->getClosure();
                $this->Result = $action();
            }
            else if ($this->Status == self::Started)
            {
                $this->Result = $this->Awaiter->getResult();
            }

            if ($this->Result instanceof TaskCanceledException)
            {
                $this->Status = self::Canceled;
            }
            else if ($this->Result instanceof TaskException)
            {
                $this->Status = self::Faulted;
            }
            else
            {
                $this->Status = self::Completed;
            }

            $this->Scheduler->remove($this);
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

    public static function fromException(string $message, int $code = 0) : Task
    {
        return Task::run(function () use($message, $code)
        {
            throw new TaskException($message, $code);
        });
    }

    public static function completedTask()
    {
        return new Task();
    }
}
