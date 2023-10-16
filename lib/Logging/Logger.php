<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

use DevNet\System\PropertyTrait;

class Logger implements ILogger
{
    use PropertyTrait;

    private LogLevel $minimumLevel;
    private array $loggers;

    public function __construct(array $loggers, LogLevel $minimumLevel = LogLevel::None)
    {
        $this->minimumLevel = $minimumLevel;
        $this->loggers = $loggers;
    }

    public function get_MinimumLevel(): LogLevel
    {
        return $this->minimumLevel;
    }

    public function log(LogLevel $level, string $message, array $args = []): void
    {
        foreach ($this->loggers as $logger) {
            if ($level->value >= $this->minimumLevel->value) {
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
        $this->log(LogLevel::Information, $message, $args);
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
