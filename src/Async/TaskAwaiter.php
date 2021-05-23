<?php declare(strict_types = 1);
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
    private Worker $Worker;
    private bool $IsComplited = false;
    private $Result = null;

    public function __construct(string $data)
    {
        $data         = base64_encode($data);
        $workspace    = escapeshellarg(LauncherProperties::getWorkspace());
        $this->Worker = new Worker('php '.__DIR__.'/Process.php '.$workspace.' '.$data);
    }

    public function __get(string $name)
    {
        if ($name == 'IsComplited')
        {
            if (!$this->IsComplited)
            {
                if (!$this->Worker->isRunning())
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
            $output = $this->Worker->read();
            $result = $this->Worker->report();

            $this->Result = unserialize($result);
            $this->Worker->close();

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
            if ($this->Worker->isRunning())
            {
                $this->Worker->stop();
                $this->Result = new TaskException('The task was canceled');
                $this->IsComplited = true;
            }
        }
    }
}
