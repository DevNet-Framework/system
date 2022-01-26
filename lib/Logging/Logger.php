<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

class Logger implements ILogger
{
    private int $MinimumLevel;
    private array $Loggers;

    public function __construct(array $loggers, array $filters)
    {
        $this->MinimumLevel = $filters[''] ?? 0;
        $this->Loggers = $loggers;
        $longestPrefix = '';
        
        foreach ($filters as $prefix => $level) {
            if (strlen($prefix) > strlen($longestPrefix)) {
                $longestPrefix = $prefix;
                $this->MinimumLevel = $level;
            }
        }
    }

    public function log(int $level, string $message, array $args = []): void
    {
        // overide the arguments if the fist and only argument is an array
        if (count($args) == 1 && isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }

        foreach ($this->Loggers as $logger) {
            if ($level >= $this->MinimumLevel) {
                $logger->log($level, $message, $args);
            }
        }
    }

    public function trace(string $message, ...$args): void
    {
        $this->log(LogLevel::Trace, $message, $args);
    }

    public function debug(string $message, ...$args): void
    {
        $this->log(LogLevel::Debug, $message, $args);
    }

    public function info(string $message, ...$args): void
    {
        $this->log(LogLevel::Info, $message, $args);
    }

    public function warning(string $message, ...$args): void
    {
        $this->log(LogLevel::Warning, $message, $args);
    }

    public function error(string $message, ...$args): void
    {
        $this->log(LogLevel::Error, $message, $args);
    }

    public function fatal(string $message, ...$args): void
    {
        $this->log(LogLevel::Fatal, $message, $args);
    }
}
