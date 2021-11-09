<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\Runtime\LauncherProperties;

class TaskAwaiter
{
    private Process $Process;
    private bool $IsComplited = false;
    private $Result = null;

    public function __construct(string $data)
    {
        $data          = base64_encode($data);
        $workspace     = escapeshellarg(LauncherProperties::getWorkspace());
        $this->Process = new Process('php ' . __DIR__ . '/Worker.php ' . $workspace . ' ' . $data);
    }

    public function __get(string $name)
    {
        if ($name == 'IsComplited') {
            if (!$this->IsComplited) {
                if (!$this->Process->isRunning()) {
                    $this->getResult();
                }
            }
        }

        return $this->$name;
    }

    public function getResult()
    {
        if (!$this->IsComplited) {
            $output = $this->Process->read();
            $result = $this->Process->report();

            $this->Result = unserialize($result);
            $this->Process->close();

            if ($output) {
                echo $output;
            }

            $this->IsComplited = true;
        }

        return $this->Result;
    }

    public function stop(): void
    {
        if (!$this->IsComplited) {
            if ($this->Process->isRunning()) {
                $this->Process->stop();
                $this->Result = new TaskException('The task was canceled');
                $this->IsComplited = true;
            }
        }
    }
}
