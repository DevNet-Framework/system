<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use Closure;
use Exception;
use Generator;

class AsyncAwaiter implements IAwaiter
{
    private ?Generator $Generator = null;
    private ?AsyncResult $AsyncResult = null;
    private ?CancelationToken $Token = null;
    private ?Closure $OnCompleted = null;
    private bool $IsCompleted = false;
    private bool $IsRunning = false;
    private $Result = null;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct($result = null, ?CancelationToken $token = null)
    {
        if ($result instanceof Generator) {
            $this->Generator = $result;
            $this->Token = $token;
        } else {
            $this->Result = $result;
            $this->IsCompleted = true;
        }
    }

    public function onCompleted(Closure $continuation): void
    {
        $this->OnCompleted = $continuation;
    }

    public function isCompleted(): bool
    {
        if ($this->IsCompleted) {
            return $this->IsCompleted;
        }

        if ($this->Token && $this->Token->IsCancellationRequested) {
            $this->IsCompleted = true;
            $this->Process->kill();
            $this->Process->close();
            throw new CancelationException('A task was canceled');
        }

        $this->next();
        return $this->IsCompleted;
    }

    public function getResult()
    {
        while (!$this->IsCompleted) {
            $this->next();
        }

        if ($this->OnCompleted) {
            $continuation = $this->OnCompleted;
            $this->OnCompleted = null;
            $continuation();
        }

        return $this->Result;
    }

    public function next(): void
    {
        if (!$this->Generator) {
            return;
        }

        if (!$this->Generator->valid()) {
            $this->Result = $this->Generator->getReturn();
            $this->IsCompleted = true;
            $this->IsRunning = false;
            return;
        } else if (!$this->IsRunning) {
            $this->IsRunning = true;
            return;
        }

        $result = $this->Generator->current();

        if ($result instanceof Generator) {
            if (!$this->AsyncResult) {
                $this->AsyncResult = new AsyncResult($result);
            }
            $result = $this->AsyncResult;
        }

        if ($result instanceof IAwaitable) {
            try {
                if ($result->getAwaiter()->isCompleted()) {
                    $this->Generator->send($result->getAwaiter()->getResult());
                    if ($this->AsyncResult) {
                        $this->AsyncResult = null;
                    }
                }
            } catch (\Throwable $exception) {
                $this->Generator->throw($exception);
            }
        } else {
            $this->Generator->send($result);
        }
    }
}
