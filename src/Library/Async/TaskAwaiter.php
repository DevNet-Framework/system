<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\Boot\LauncherProperties;
use Exception;

class TaskAwaiter
{
    private Process $Process;
    private bool $IsComplited = false;
    private $Result = null;

    public function __construct()
    {
        $this->Process = new Process('php '.__DIR__.'/Worker.php');
    }

    public function __get(string $name)
    {
        if ($name == 'IsComplited')
        {
            if (!$this->IsComplited)
            {
                if (!$this->Process->isRunning())
                {
                    $this->getResult();
                }
            }
        }

        return $this->$name;
    }

    public function getResult()
    {
        if (!$this->IsComplited)
        {
            $output = $this->Process->getOutput();
            $result = $this->Process->getReport();

            $this->Result = unserialize($result);
            $this->Process->close();

            if ($output)
            {
                echo $output;
            }

            $this->IsComplited = true;
        }

        return $this->Result;
    }

    public function stop() : void
    {
        if (!$this->IsComplited)
        {
            if ($this->Process->isRunning())
            {
                $this->Process->stop();
                $this->Result = new TaskException('The task was canceled');
                $this->IsComplited = true;
            }
        }
    }
}
