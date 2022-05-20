<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

use DevNet\System\Exceptions\PropertyException;

class Logger implements ILogger
{
    private int $minimumLevel;
    private array $loggers;
    
    public function __get(string $name)
    {
        if ($name == 'MinimumLevel') {
            return $this->minimumLevel;
        }
        
        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(array $loggers, int $minimumLevel = 0)
    {
        $this->minimumLevel = $minimumLevel;
        $this->loggers = $loggers;
    }

    public function log(int $level, string $message, array $args = []): void
    {
        // overide the arguments if the fist and only argument is an array
        if (count($args) == 1 && isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }

        foreach ($this->loggers as $logger) {
            if ($level >= $this->minimumLevel) {
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
