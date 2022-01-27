<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

use DevNet\System\Logging\Console\ConsoleLoggerProvider;

class LoggerOptions
{
    use \DevNet\System\Extension\ExtenderTrait;

    private array $Filters = [];
    private array $Providers = [];

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function setMinimumLevel(int $level)
    {
        $this->addFilter('', $level);
    }
    
    public function addFilter(string $category, int $level)
    {
        $this->Filters[$category] = $level;
    }

    public function addProvider(ILoggerProvider $provider): void
    {
        $this->Providers[get_class($provider)] = $provider;
    }

    public function addConsole(): void
    {
        $this->addProvider(new ConsoleLoggerProvider());
    }
}
