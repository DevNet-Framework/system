<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async\Tasks;

use DevNet\System\Process;
use DevNet\System\Async\IAwaiter;
use DevNet\System\Async\CancelationException;
use DevNet\System\Async\CancelationToken;
use DevNet\System\Loader\LauncherProperties;
use DevNet\System\Action;
use Closure;

class TaskAwaiter implements IAwaiter
{
    private Process $Process;
    private ?CancelationToken $Token;
    private ?Closure $OnCompleted = null;
    private bool $IsCompleted = false;
    private $Result = null;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(Action $action, ?CancelationToken $token = null)
    {
        $action    = serialize($action);
        $action    = base64_encode($action);
        $workspace = escapeshellarg(LauncherProperties::getWorkspace());

        $this->Token   = $token;
        $this->Process = new Process();
        $this->Process->start('php', __DIR__ . '/Internal/TaskWorker.php', $workspace, $action);
    }

    public function onCompleted(Closure $continuation): void
    {
        $this->OnCompleted = $continuation;
    }

    function isCompleted(): bool
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

        $isRunning = $this->Process->isRunning();
        if (!$isRunning) {
            $this->IsCompleted = true;
        }

        return $this->IsCompleted;
    }

    public function getResult()
    {
        if ($this->Result) {
            return $this->Result;
        }

        while (!$this->isCompleted()) {
            // waiting for process to be completed.
        }

        $output = $this->Process->read();
        $result = $this->Process->report();
        $this->Process->close();
        $this->Result = unserialize($result);
        $this->IsCompleted = true;

        if ($this->Result instanceof TaskException) {
            throw new TaskException($this->Result->getMessage(), $this->Result->getCode());
        }

        echo $output;
        if ($this->OnCompleted) {
            $continuation = $this->OnCompleted;
            $this->OnCompleted = null;
            $continuation();
        }

        return $this->Result;
    }
}
