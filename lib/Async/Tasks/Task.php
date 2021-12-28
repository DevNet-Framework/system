<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async\Tasks;

use DevNet\System\Action;
use DevNet\System\Async\AsyncAwaiter;
use DevNet\System\Async\CancelationException;
use DevNet\System\Async\CancelationToken;
use DevNet\System\Async\IAwaitable;
use DevNet\System\Async\IAwaiter;
use DevNet\System\Exceptions\ArrayException;
use Closure;

class Task implements IAwaitable
{
    public const Created   = 0;
    public const Pending   = 1;
    public const Running   = 2;
    public const Succeeded = 3;
    public const Canceled  = -1;
    public const Failed    = -2;

    private int $Id;
    private int $Status = 0;
    private TaskScheduler $Scheduler;
    private ?Task $continuationTask = null;
    private ?Action $Action = null;
    private ?IAwaiter $Awaiter = null;
    private ?CancelationToken $Token = null;
    private bool $IsCompleted = false;
    private $Result = null;

    public function __get(string $name)
    {
        switch ($name) {
            case 'IsCompleted':
                if (!$this->IsCompleted && $this->Status == Task::Running) {
                    $this->IsCompleted = $this->Awaiter->IsCompleted();
                }
                break;
            case 'Result':
                if (!$this->IsCompleted) {
                    $this->wait();
                }
                break;
        }

        return $this->$name;
    }

    public function __construct(Closure $action = null, ?CancelationToken $token = null)
    {
        $this->Id        = spl_object_id($this);
        $this->Status    = Self::Succeeded;
        $this->Scheduler = TaskScheduler::getDefaultScheduler();
        $this->Awaiter   = new AsyncAwaiter();
        $this->Token     = $token;

        if ($action) {
            $this->Action = new Action($action);
            $this->Status = Self::Created;
        }
    }

    public function getAwaiter(): IAwaiter
    {
        return $this->Awaiter;
    }

    public function start(TaskScheduler $taskScheduler = null): void
    {
        if ($this->Status == Task::Running || $this->IsCompleted) {
            return;
        }

        if ($taskScheduler) {
            $this->Scheduler = $taskScheduler;
        }

        $this->Status = Self::Pending;
        $continuationAction = $this->Awaiter->OnCompleted;

        if ($this->Scheduler->MaxConcurrency == 0 || $this->Scheduler->MaxConcurrency - count($this->Scheduler->getScheduledTasks()) > 0) {
            if ($this->Action->MethodInfo->isGenerator()) {
                $action = $this->Action->MethodInfo->getClosure();
                $this->Awaiter = new AsyncAwaiter($action(), $this->Token);
            } else {
                $this->Awaiter = new TaskAwaiter($this->Action, $this->Token);
            }
            $this->Status = Task::Running;
        }

        if ($continuationAction) {
            $this->Awaiter->onCompleted($continuationAction);
        }

        $this->Scheduler->enqueue($this);
    }

    public function wait(): void
    {
        if ($this->IsCompleted) {
            return;
        }

        if ($this->Status == Task::Created || $this->Status == Task::Pending) {
            $continuationAction = $this->Awaiter->OnCompleted;
            $action = $this->Action->MethodInfo->getClosure();
            $this->Awaiter = new AsyncAwaiter($action(), $this->Token);
            if ($continuationAction) {
                $this->Awaiter->onCompleted($continuationAction);
            }
            $this->Status = Task::Running;
        }

        if ($this->Status == Task::Running) {
            try {
                $this->IsCompleted = true;
                $this->Result = $this->Awaiter->getResult();
                $this->Scheduler->dequeue($this);
            } catch (\Throwable $exception) {
                if ($exception instanceof CancelationException) {
                    $this->Status = self::Canceled;
                    throw $exception;
                }

                $this->Status = self::Failed;
                throw $exception;
            }

            $this->Status = Task::Succeeded;
        }
    }

    public function then(Closure $continuationAction, ?CancelationToken $token = null): Task
    {
        $previousTask = $this;
        $continuationAction = function () use ($continuationAction, $previousTask) {
            yield $previousTask;
            return $continuationAction($previousTask);
        };

        $continuationTask = new Task($continuationAction, $token);

        $this->Awaiter->onCompleted(function () use ($continuationTask) {
            $continuationTask->wait();
        });

        return $continuationTask;
    }

    public static function run(Closure $action, ?CancelationToken $token = null): Task
    {
        $task = new Task($action, $token);
        $task->start();
        return $task;
    }

    public static function delay(int $microseconds): Task
    {
        $task = new Task(function () use ($microseconds) {
            usleep($microseconds);
            return true;
        });
        $task->start();
        return $task;
    }

    public static function fromResult($result): Task
    {
        $task = new Task(function () use ($result) {
            return $result;
        });
        $task->wait();
        return $task;
    }

    public static function fromException(string $message, int $code = 0): Task
    {
        $task = new Task(function () use ($message, $code) {
            return new TaskException($message, $code);
        });
        $task->wait();
        return $task;
    }

    public static function completedTask(): Task
    {
        return new Task();
    }

    public static function waitAll(array $tasks): void
    {
        while ($tasks) {
            foreach ($tasks as $index => $task) {
                if (!$task instanceof Task) {
                    throw new ArrayException("The Item of the index {$index}, must be of type: " . Task::class);
                }
                if ($task->getAwaiter()->IsCompleted()) {
                    $task->getAwaiter()->getResult();
                    unset($tasks[$index]);
                }
            }
        }
    }
}
