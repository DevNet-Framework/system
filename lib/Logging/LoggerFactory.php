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

    public function __construct(array $providers)
    {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
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
        return new Logger($loggers);
    }

    public static function create(Closure $configure)
    {
        $builder = new LoggingBuilder();
        $configure($builder);
        return new LoggerFactory($builder->Providers);
    }
}
