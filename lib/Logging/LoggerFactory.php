<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

use DevNet\System\Logging\ILoggerFactory;
use Closure;

class LoggerFactory implements ILoggerFactory
{
    private array $providers = [];
    private array $filters = [];

    public function __construct(array $providers, array $filters)
    {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }

        $this->filters = $filters;
    }

    public function addProvider(ILoggerProvider $provider): void
    {
        $this->providers[get_class($provider)] = $provider;
    }

    public function createLogger(string $category): Logger
    {
        return new Logger($category, $this->providers, $this->filters);
    }

    public static function create(?Closure $configure = null): LoggerFactory
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
