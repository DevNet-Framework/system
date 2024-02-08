<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

class Logger implements ILogger
{
    private LogLevel $minimumLevel;
    private array $loggers = [];

    public function __construct(string $category, array $providers, array $filters = [])
    {
        foreach ($providers as $provider) {
            $this->loggers[] = $provider->createLogger($category);
        }

        $longestPrefix = '';
        foreach ($filters as $prefix => $level) {
            if (str_starts_with($category, $prefix)) {
                if (strlen($prefix) > strlen($longestPrefix)) {
                    $longestPrefix = $prefix;
                }
            }
        }

        $this->minimumLevel = $filters[$longestPrefix] ?? LogLevel::None;
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
