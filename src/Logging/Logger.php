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
    private array $Loggers;

    public function __construct(array $loggers)
    {
        $this->Loggers  = $loggers;
    }

    public function log(int $level, string $message, array $args): void
    {
        foreach ($this->Loggers as $logger) {
            $logger->log($level, $message, $args);
        }
    }

    public function debug(string $message, ...$args): void
    {
        // overide the arguments if the fist argument is an array
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }
        
        $this->log(LogLevel::Debug, $message, $args);
    }

    public function notice(string $message, ...$args): void
    {
        // overide the arguments if the fist argument is an array
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }
        
        $this->log(LogLevel::Notice, $message, $args);
    }

    public function warning(string $message, ...$args): void
    {
        // overide the arguments if the fist argument is an array
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }

        $this->log(LogLevel::Warning, $message, $args);
    }

    public function error(string $message, ...$args): void
    {
        // overide the arguments if the fist argument is an array
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }

        $this->log(LogLevel::Error, $message, $args);
    }

    public function critical(string $message, ...$args): void
    {
        // overide the arguments if the fist argument is an array
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }

        $this->log(LogLevel::Critical, $message, $args);
    }
}
