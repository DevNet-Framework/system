<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

use DevNet\System\Logging\ILogger;
use DevNet\System\Logging\ILoggerFactory;
use Closure;

class LoggerFactory implements ILoggerFactory
{
    private array $Providers = [];
    private array $Filters = [];

    public function __construct(array $providers, array $filters)
    {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }

        $this->Filters = $filters;
    }

    public function addProvider(ILoggerProvider $provider)
    {
        $this->Providers[get_class($provider)] = $provider;
    }

    public function createLogger(string $category): ILogger
    {
        $loggers = [];
        foreach ($this->Providers as $provider) {
            $loggers[] = $provider->createLogger($category);
        }

        $longestPrefix = '';
        foreach ($this->Filters as $prefix => $level) {
            if (str_starts_with($category, $prefix)) {
                if (strlen($prefix) > strlen($longestPrefix)) {
                    $longestPrefix = $prefix;
                }
            }
        }

        $minimumLevel = $this->Filters[$longestPrefix] ?? 0;
        return new Logger($loggers, $minimumLevel);
    }

    public static function create(?Closure $configure = null)
    {
        $providers = [];
        $filters = [];

        if ($configure) {
            $builder = new LoggerOptions();
            $configure($builder);
            $providers = $builder->Providers;
            $filters = $builder->Filters;
        }

        return new LoggerFactory($providers, $filters);
    }
}
