<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\Runtime\LauncherProperties;
use DevNet\System\Process;
use Opis\Closure\SerializableClosure;
use Closure;

class Task
{
    public const Created   = 0;
    public const Pending   = 1;
    public const Running   = 2;
    public const Succeeded = 3;
    public const Canceled  = -1;
    public const Failed    = -2;

    private int $Id;
    private int $Status = 0;
    private Process $Process;
    private TaskScheduler $Scheduler;
    private ?TaskAwaiter $Awaiter = null;
    private ?CancelationToken $Token = null;
    private bool $IsCompleted = false;
    private $Action = null;
    private $Result = null;

    public function __get(string $name)
    {
        switch ($name) {
            case 'Status':
                if ($this->Process->isRunning() && $this->Status != Task::Pending) {
                    $this->IsCompleted = !$this->Process->isRunning();
                }
                break;
            case 'IsCompleted':
                if (!$this->Process->isRunning() && $this->Status != Task::Pending) {
                    $this->IsCompleted = !$this->Process->isRunning();
                }
                break;
            case 'Result':
                if ($this->Status == self::Pending || $this->Status == self::Running) {
                    $this->wait();
                }
                break;
        }

        return $this->$name;
    }

    public function __construct(callable $action = null, ?CancelationToken $token = null)
    {
        $this->Id        = spl_object_id($this);
        $this->Status    = Self::Succeeded;
        $this->Scheduler = TaskScheduler::getDefaultScheduler();
        $this->Awaiter   = new TaskAwaiter($this);
        $this->Token     = $token;

        if ($action) {
            $this->Action  = Closure::fromCallable($action);
            $this->Action  = new SerializableClosure($this->Action);
            $this->Process = new Process();
            $this->Status  = Self::Created;
        }
    }

    public function getAwaiter(): TaskAwaiter
    {
        return $this->Awaiter;
    }

    public function start(TaskScheduler $taskScheduler = null): void
    {
        if ($taskScheduler) {
            $this->Scheduler = $taskScheduler;
        }

        $this->Status = Self::Pending;
        $this->Scheduler->enqueue($this);

        if ($this->Scheduler->MaxConcurrency == 0 || $this->Scheduler->MaxConcurrency - count($this->Scheduler->getScheduledTasks()) > 0) {
            $action        = serialize($this->Action);
            $action        = base64_encode($action);
            $workspace     = escapeshellarg(LauncherProperties::getWorkspace());
            $this->Process->start('php '. __DIR__ . '/Internal/TaskWorker.php '. $workspace.' '. $action);
            $this->Status = Task::Running;
        }
    }

    public function wait(): void
    {
        if ($this->Status == Task::Pending) {
            if ($this->Token && $this->Token->IsCancellationRequested) {
                $this->Status == Task::Canceled;
                throw new CancelationException('The task was canncled');
            }
            $action = $this->Action;
            $this->Result = $action();
            return;
        }

        if ($this->Status == Task::Running) {
            $output = $this->Process->read();
            $result = $this->Process->report();

            $this->Result = unserialize($result);
            $this->Process->close();
            $this->Scheduler->dequeue($this);

            if ($this->Result instanceof CancelationException) {
                $this->Status = self::Canceled;
                throw new CancelationException($this->Result->getMessage(), $this->Result->getCode());
            } else if ($this->Result instanceof TaskException) {
                $this->Status = self::Failed;
                throw new TaskException($this->Result->getMessage(), $this->Result->getCode());
            }

            $this->Status = Task::Succeeded;
            $this->IsCompleted = true;
            echo $output;
        }
    }

    public function then(Closure $next, ?CancelationToken $token = null): Task
    {
        $this->wait();
        $precedent = $this;
        $next = function () use ($next, $precedent) {
            return $next($precedent);
        };

        return Task::run($next, $token);
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

    public static function completedTask()
    {
        return new Task();
    }
}
