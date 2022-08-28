<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

use DevNet\System\Logging\Console\ConsoleLoggerProvider;
use DevNet\System\Logging\File\FileLoggerProvider;
use DevNet\System\ObjectTrait;

class LoggerOptions
{
    use ObjectTrait;

    private array $filters = [];
    private array $providers = [];

    public function get_Filters(): array
    {
        return $this->filters;
    }

    public function get_Providers(): array
    {
        return $this->providers;
    }

    public function setMinimumLevel(int $level)
    {
        $this->addFilter('', $level);
    }

    public function addFilter(string $category, int $level)
    {
        $this->filters[$category] = $level;
    }

    public function addProvider(ILoggerProvider $provider): void
    {
        $this->providers[get_class($provider)] = $provider;
    }

    public function addConsole(): void
    {
        $this->addProvider(new ConsoleLoggerProvider());
    }

    public function addFile(string $fileName): void
    {
        $this->addProvider(new FileLoggerProvider($fileName));
    }
}
