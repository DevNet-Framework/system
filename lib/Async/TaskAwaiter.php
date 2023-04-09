<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\ObjectTrait;
use Generator;
use Closure;

class TaskAwaiter implements IAwaiter
{
    use ObjectTrait;

    private ?Generator $generator = null;
    private ?AsyncResult $asyncResult = null;
    private ?CancelationToken $token = null;
    private ?Closure $onCompleted = null;
    private bool $isCompleted = false;
    private bool $isRunning = false;
    private $result = null;

    public function __construct($result = null, ?CancelationToken $token = null)
    {
        if ($result instanceof Generator) {
            $this->generator = $result;
            $this->token = $token;
        } else {
            $this->result = $result;
            $this->isCompleted = true;
        }
    }

    public function get_OnCompleted(): ?Closure
    {
        return $this->onCompleted;
    }

    public function onCompleted(Closure $continuation): void
    {
        $this->onCompleted = $continuation;
    }

    public function isCompleted(): bool
    {
        if ($this->isCompleted) {
            return $this->isCompleted;
        }

        if ($this->token && $this->token->IsCancellationRequested) {
            $this->isCompleted = true;
            $this->generator->throw(new CancelationException('The task was canceled!'));
        }

        $this->next();
        return $this->isCompleted;
    }

    public function getResult()
    {
        while (!$this->isCompleted) {
            $this->next();
        }

        if ($this->onCompleted) {
            $continuation = $this->onCompleted;
            $this->onCompleted = null;
            $continuation();
        }

        return $this->result;
    }

    public function next(): void
    {
        if (!$this->generator) {
            return;
        }

        if (!$this->generator->valid()) {
            try {
                $this->result = $this->generator->getReturn();
            } catch (\Throwable $error) {
                $this->result = null;
            }
            $this->isCompleted = true;
            $this->isRunning = false;
            return;
        } else if (!$this->isRunning) {
            $this->isRunning = true;
            return;
        }

        $result = $this->generator->current();
        $this->generator->send($result);
    }
}
