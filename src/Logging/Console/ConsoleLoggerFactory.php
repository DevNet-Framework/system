<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging\Console;

use DevNet\System\Logging\ILogger;
use DevNet\System\Logging\ILoggerFactory;

class ConsoleLoggerFactory implements ILoggerFactory
{
    public function createLogger(string $category): ILogger
    {
        return new ConsoleLogger($category);
    }
}
