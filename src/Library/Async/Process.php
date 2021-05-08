<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

class Process
{
    private string $Command;
    private array $Descriptors;
    private array $Pipes = [];
    private ?string $Cwd;
    private ?array $Env;
    private $Process;

    public function __construct(string $command, ?string $cwd = null, array $env = null)
    {
        $this->Command = $command;
        $this->Descriptors = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];

        $this->Cwd = $cwd;
        $this->Env = $env;
        $this->Env = $env;
    }

    public function start()
    {
        $this->Process = proc_open($this->Command, $this->Descriptors, $this->Pipes, $this->Cwd, $this->Env);

        if (!is_resource($this->Process))
        {
            throw new \RuntimeException("Error Processing Request");
        }
    }

    public function isRunning() : bool
    {
        if (is_resource($this->Process))
        {
            $status = proc_get_status($this->Process);
            return $status['running'];
        }

        return false;
    }

    public function getPid() : ?string
    {
        if (is_resource($this->Process))
        {
            $status = proc_get_status($this->Process);
            return $status['pid'];
        }

        return null;
    }

    public function write(string $data) : void
    {
        fwrite($this->Pipes[0], $data);
    }

    public function read(int $chunk = 0) : ?string
    {
        $result = null;
        if (is_resource($this->Process))
        {
            if ($chunk > 0)
            {
                $result = fread($this->Pipes[1], 221024);
            }
            else
            {
                $result = stream_get_contents(($this->Pipes[1]));
            }
        }

        return $result;
    }

    public function report(int $chunk = 0) : ?string
    {
        $result = null;
        if (is_resource($this->Process))
        {
            if ($chunk > 0)
            {
                $result = fread($this->Pipes[2], 221024);
            }
            else
            {
                $result = stream_get_contents(($this->Pipes[2]));
            }
        }

        return $result;
    }

    public function stop()
    {
        if ($this->isRunning())
        {
            if (function_exists('proc_terminate'))
            {
                proc_terminate($this->Process);
                return;
            }
            
            $pid = $this->getPid();
            PHP_OS_FAMILY == 'Windows' ? exec("taskkill /F /T /PID $pid") : exec("kill -9 $pid");
            $this->close();
        }
    }

    public function close()
    {
        if (is_resource($this->Process))
        {
            fclose($this->Pipes[0]);
            fclose($this->Pipes[1]);
            fclose($this->Pipes[2]);
            proc_close($this->Process);
        }
    }
}