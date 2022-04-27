<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async\Tasks;

use DevNet\System\Action;
use DevNet\System\Async\IAwaiter;
use DevNet\System\Async\CancelationException;
use DevNet\System\Async\CancelationToken;
use DevNet\System\Process;
use DevNet\System\Runtime\LauncherProperties;
use Closure;

class TaskAwaiter implements IAwaiter
{
    private Process $process;
    private ?CancelationToken $token;
    private ?Closure $onCompleted = null;
    private bool $isCompleted = false;
    private $result = null;

    public function __construct(Action $action, ?CancelationToken $token = null)
    {
        $action    = serialize($action);
        $action    = base64_encode($action);
        $workspace = escapeshellarg(LauncherProperties::getWorkspace());

        $this->token   = $token;
        $this->process = new Process();
        $this->process->start('php', __DIR__ . '/Internal/TaskWorker.php', $workspace, $action);
    }

    public function onCompleted(Closure $continuation): void
    {
        $this->onCompleted = $continuation;
    }

    function isCompleted(): bool
    {
        if ($this->isCompleted) {
            return $this->isCompleted;
        }
        
        if ($this->token && $this->token->IsCancellationRequested) {
            $this->isCompleted = true;
            $this->process->kill();
            $this->process->close();
            throw new CancelationException('A task was canceled');
        }

        $isRunning = $this->process->isRunning();
        if (!$isRunning) {
            $this->isCompleted = true;
        }

        return $this->isCompleted;
    }

    public function getResult()
    {
        if ($this->result) {
            return $this->result;
        }

        // waiting for process to be completed.
        while (true) {
            if ($this->isCompleted()) {
                break;
            }
        }

        $output = $this->process->read();
        $result = $this->process->report();
        $this->process->close();
        $this->result = unserialize($result);
        $this->isCompleted = true;

        if ($this->result instanceof TaskException) {
            throw new TaskException($this->result->getMessage(), $this->result->getCode());
        }

        echo $output;
        if ($this->onCompleted) {
            $continuation = $this->onCompleted;
            $this->onCompleted = null;
            $continuation();
        }

        return $this->result;
    }
}
