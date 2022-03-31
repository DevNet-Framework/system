<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

use DevNet\System\Exceptions\PropertyException;
use DevNet\System\Logging\Console\ConsoleLoggerProvider;
use DevNet\System\Logging\File\FileLoggerProvider;

class LoggerOptions
{
    use \DevNet\System\Extension\ExtenderTrait;

    private array $filters = [];
    private array $providers = [];

    public function __get(string $name)
    {
        if ($name == 'Filters') {
            return $this->filters;
        }

        if ($name == 'Providers') {
            return $this->filters;
        }
        
        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
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
