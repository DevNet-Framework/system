<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
            ["pipe", "r"],
            ["pipe", "w"],
            ["pipe", "w"]
        ];

        $this->cwd = $cwd;
        $this->env = $env;
    }

    public function start(string ...$command): void
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

    public function input(string $data): void
    {
        fwrite($this->pipes[0], $data);
    }

    public function output(): ?string
    {
        $result = null;
        if (is_resource($this->process)) {
            $result = stream_get_contents($this->pipes[1]);
            if ($result === false) {
                $result = null;
            }
        }

        return $result;
    }

    public function report(): ?string
    {
        $result = null;
        if (is_resource($this->process)) {
            $result = stream_get_contents($this->pipes[2]);
            if ($result === false) {
                $result = null;
            }
        }

        return $result;
    }

    public function kill(): void
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

    public function close(): void
    {
        if (is_resource($this->process)) {
            fclose($this->pipes[0]);
            fclose($this->pipes[1]);
            fclose($this->pipes[2]);
            proc_close($this->process);
        }
    }
}
