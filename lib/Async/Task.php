<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\Exceptions\ArrayException;
use DevNet\System\PropertyTrait;
use Closure;
use Exception;
use Generator;
use Throwable;

class Task implements IAwaitable
{
    use PropertyTrait;

    private int $id;
    private TaskStatus $status;
    private TaskAwaiter $awaiter;
    private TaskScheduler $scheduler;
    private Generator $generator;
    private ?Task $continuationTask = null;
    private ?CancellationToken $token = null;
    private ?Throwable $exception = null;

    public function __construct(Closure $action, ?CancellationToken $token = null)
    {
        $this->id        = spl_object_id($this);
        $this->awaiter   = new TaskAwaiter($this);
        $this->status    = TaskStatus::Created;
        $this->scheduler = TaskScheduler::getDefaultScheduler();
        $this->token     = $token;

        $reflector = new \ReflectionFunction($action);
        if ($reflector->isGenerator()) {
            $this->generator = $action();
        } else {
            $action = function () use ($action) {
                yield;
                return $action();
            };
            $this->generator = $action();
        }
    }

    public function get_Id(): int
    {
        return $this->id;
    }

    public function get_Status(): TaskStatus
    {
        return $this->status;
    }

    public function get_Result(): mixed
    {
        $this->wait();

        try {
            return $this->generator->getReturn();
        } catch (\Throwable $error) {
            return null;
        }
    }

    public function get_IsCompleted(): bool
    {
        if ($this->status == TaskStatus::Canceled || $this->status == TaskStatus::Failed || $this->status == TaskStatus::Succeeded) {
            return true;
        }

        if ($this->status == TaskStatus::Created || $this->status == TaskStatus::Pending) {
            return false;
        }

        if ($this->generator->valid()) {
            if ($this->token && $this->token->IsCancellationRequested) {
                try {
                    $this->generator->throw(new CancellationException("The task with Id: {$this->id} was canceled!"));
                } catch (\Throwable $exception) {
                    $this->exception = $exception;
                    $this->status = TaskStatus::Canceled;
                }
            } else {
                try {
                    $value = $this->generator->current();
                    $this->generator->send($value);
                } catch (\Throwable $exception) {
                    $this->exception = $exception;
                    $this->status = TaskStatus::Failed;
                }
            }
        } else {
            if (!$this->exception) {
                $this->status = TaskStatus::Succeeded;
            }
            $this->scheduler->dequeue($this);
        }

        return !$this->generator->valid();
    }

    public function start(?TaskScheduler $taskScheduler = null): void
    {
        if ($this->status == TaskStatus::Running) {
            return;
        }

        if ($taskScheduler) {
            $this->scheduler = $taskScheduler;
        }

        $this->status = TaskStatus::Pending;
        if ($this->scheduler->MaxConcurrency == 0 || $this->scheduler->MaxConcurrency - count($this->scheduler->getScheduledTasks()) > 0) {
            $this->status = TaskStatus::Running;
            try {
                $this->generator->valid();
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
        while (true) {
            if ($this->IsCompleted) {
                break;
            }
        }

        if ($this->exception) {
            throw $this->exception;
        }
    }

    public function then(Closure $continuationAction, ?CancellationToken $token = null): Task
    {
        $previousTask = $this;
        $continuationTask = Task::run(function () use ($continuationAction, $previousTask) {
            while (!$previousTask->IsCompleted) {
                yield;
            }
            $result = $continuationAction($previousTask);
            if ($result instanceof Generator) {
                while ($result->valid()) {
                    yield;
                    $value = $result->current();
                    $result->send($value);
                }

                try {
                    return $result->getReturn();
                } catch (\Throwable $error) {
                    return null;
                }
            } else {
                return $result;
            }
        }, $token);

        return $continuationTask;
    }

    public function getAwaiter(): IAwaiter
    {
        return $this->awaiter;
    }

    public static function run(Closure $action, ?CancellationToken $token = null): Task
    {
        $task = new Task($action, $token);
        $task->start();
        return $task;
    }

    public static function delay(float $seconds): Task
    {
        $task = Task::run(function () use ($seconds) {
            $startTime = microtime(true);
            do {
                yield;
                $elapsedTime = microtime(true) - $startTime;
            } while ($elapsedTime < $seconds);
            return true;
        });

        return $task;
    }

    public static function fromResult(mixed $result): Task
    {
        $task = Task::run(function () use ($result) {
            if ($result instanceof Exception) {
                throw $result;
            }
            return $result;
        });

        // wait for the task to be completed without throwing the exception.
        while (true) {
            if ($task->IsCompleted) {
                break;
            }
        }

        return $task;
    }

    public static function fromException(Exception $exception): Task
    {
        return Task::fromResult($exception);
    }

    public static function completedTask(): Task
    {
        return Task::fromResult(null);
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
