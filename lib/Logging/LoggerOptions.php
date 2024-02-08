<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

use DevNet\System\Logging\Console\ConsoleLoggerProvider;
use DevNet\System\Logging\File\FileLoggerProvider;
use DevNet\System\PropertyTrait;

class LoggerOptions
{
    use PropertyTrait;

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

    public function setMinimumLevel(LogLevel $level): void
    {
        $this->addFilter('', $level);
    }

    public function addFilter(string $category, LogLevel $level): void
    {
        $this->filters[$category] = $level;
    }

    public function addProvider(ILoggerProvider $provider): void
    {
        $this->providers[$provider::class] = $provider;
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
