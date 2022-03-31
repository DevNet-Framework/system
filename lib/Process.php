<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

class Process
{
    private array $descriptors;
    private array $pipes = [];
    private ?string $cwd;
    private ?array $env;
    private $process;

    public function __construct(?string $cwd = null, array $env = null)
    {
        $this->descriptors = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];

        $this->cwd = $cwd;
        $this->env = $env;
    }

    public function start(string ...$command)
    {
        $command = implode(' ', $command);
        $this->process = proc_open($command, $this->descriptors, $this->pipes, $this->cwd, $this->env);

        if (!is_resource($this->process)) {
            throw new \RuntimeException("Error Processing Request");
        }
    }

    public function isRunning(): bool
    {
        if (is_resource($this->process)) {
            $status = proc_get_status($this->process);
            return $status['running'];
        }

        return false;
    }

    public function getPid(): ?string
    {
        if (is_resource($this->process)) {
            $status = proc_get_status($this->process);
            return $status['pid'];
        }

        return null;
    }

    public function write(string $data): void
    {
        fwrite($this->pipes[0], $data);
    }

    public function read(int $chunk = 0): ?string
    {
        $result = null;
        if (is_resource($this->process)) {
            if ($chunk > 0) {
                $result = fread($this->pipes[1], 221024);
            } else {
                $result = stream_get_contents(($this->pipes[1]));
            }
        }

        return $result;
    }

    public function report(int $chunk = 0): ?string
    {
        $result = null;
        if (is_resource($this->process)) {
            if ($chunk > 0) {
                $result = fread($this->pipes[2], 221024);
            } else {
                $result = stream_get_contents(($this->pipes[2]));
            }
        }

        return $result;
    }

    public function kill()
    {
        if ($this->isRunning()) {
            if (function_exists('proc_terminate')) {
                proc_terminate($this->process);
                return;
            }

            $pid = $this->getPid();
            PHP_OS_FAMILY == 'Windows' ? exec("taskkill /F /T /PID $pid") : exec("kill -9 $pid");
            $this->close();
        }
    }

    public function close()
    {
        if (is_resource($this->process)) {
            fclose($this->pipes[0]);
            fclose($this->pipes[1]);
            fclose($this->pipes[2]);
            proc_close($this->process);
        }
    }
}
