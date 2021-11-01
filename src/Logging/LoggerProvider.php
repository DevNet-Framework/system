<?php
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

use DevNet\System\Logging\Console\ConsoleLoggerFactory;

class LoggerProvider
{
    use \DevNet\System\Extension\ExtensionTrait;
    
    private array $Factories = [];

    public function add(ILoggerFactory $factory): void
    {
        $this->Factories[] = $factory;
    }

    public function addConsoleLogger(): void
    {
        $this->Factories[] = new ConsoleLoggerFactory();
    }

    public function getLogger(string $category): ?Logger
    {
        if (!$this->Factories)
        {
            return null;
        }
        
        foreach ($this->Factories as $factory) {
            $loggers[] = $factory->createLogger($category);
        }

        return new Logger($loggers);
    }
}
