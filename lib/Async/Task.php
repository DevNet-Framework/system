<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\Exceptions\ArrayException;
use DevNet\System\ObjectTrait;
use Closure;
use Fiber;
use Throwable;

class Task implements IAwaitable
{
    use ObjectTrait;

    private int $id;
    private Fiber $fiber;
    private TaskAwaiter $awaiter;
    private TaskStatus $status;
    private mixed $asyncResult;
    private TaskScheduler $scheduler;
    private ?Task $continuationTask = null;
    private ?CancelationToken $token = null;
    private ?Throwable $exception = null;

    public function __construct(Closure $action, ?CancelationToken $token = null)
    {
        $this->id        = spl_object_id($this);
        $this->fiber     = new Fiber($action);
        $this->awaiter   = new TaskAwaiter($this);
        $this->status    = TaskStatus::Created;
        $this->scheduler = TaskScheduler::getDefaultScheduler();
        $this->token     = $token;
    }

    public function get_Id(): int
    {
        return $this->id;
    }

    public function get_Status(): TaskStatus
    {
        return $this->status;
    }

    public function get_IsCompleted(): bool
    {
        if ($this->fiber->isTerminated()) {
            return true;
        }

        if ($this->token && $this->token->IsCancellationRequested) {
            try {
                $this->fiber->throw(new CancelationException("The task with Id: {$this->id} was canceled!"));
            } catch (\Throwable $exception) {
                $this->exception = $exception;
                $this->status = TaskStatus::Canceled;
            }
        }

        if ($this->fiber->isSuspended()) {
            try {
                $this->asyncResult = $this->fiber->resume($this->asyncResult);
            } catch (\Throwable $exception) {
                $this->exception = $exception;
                $this->status = TaskStatus::Failed;
            }
        }

        if ($this->fiber->isTerminated()) {
            if (!$this->exception) {
                $this->status = TaskStatus::Succeeded;
            }
            $this->scheduler->dequeue($this);
        }

        return false;
    }

    public function get_Result(): mixed
    {
        $this->wait();

        if ($this->exception) {
            return $this->exception;
        }

        return $this->fiber->getReturn();
    }

    public function start(TaskScheduler $taskScheduler = null): void
    {
        if ($this->fiber->isStarted()) {
            return;
        }

        if ($taskScheduler) {
            $this->scheduler = $taskScheduler;
        }

        $this->status = TaskStatus::Pending;
        if ($this->scheduler->MaxConcurrency == 0 || $this->scheduler->MaxConcurrency - count($this->scheduler->getScheduledTasks()) > 0) {

            try {
                $this->asyncResult = $this->fiber->start();
                $this->status = TaskStatus::Running;
            } catch (\Throwable $exception) {
                $this->exception = $exception;
                $this->status = TaskStatus::Failed;
                return;
            }
        }

        $this->scheduler->enqueue($this);
    }

    public function wait(): void
    {
        while (!$this->IsCompleted) {
            // 
        }
    }

    public function then(Closure $continuationAction, ?CancelationToken $token = null): Task
    {
        $previousTask = $this;
        $continuationTask = new Task(function () use ($continuationAction, $previousTask) {
            while (!$previousTask->IsCompleted) {
                Fiber::suspend();
            }
            return $continuationAction($previousTask);
        }, $token);

        $this->awaiter->onCompleted(function () use ($continuationTask) {
            $continuationTask->wait();
        });

        return $continuationTask;
    }

    public function getAwaiter(): IAwaiter
    {
        return $this->awaiter;
    }

    public static function run(Closure $action, ?CancelationToken $token = null): Task
    {
        $task = new Task($action, $token);
        $task->start();
        return $task;
    }

    public static function delay(float $seconds): Task
    {
        $task = new Task(function () use ($seconds) {
            $startTime = microtime(true);
            do {
                Fiber::suspend();
                $elapsedTime = microtime(true) - $startTime;
            } while ($elapsedTime < $seconds);
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
        $task = new Task(function () {
            return null;
        });
        $task->wait();
        return $task;
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
