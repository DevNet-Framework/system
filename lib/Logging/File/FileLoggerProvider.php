<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging\File;

use DevNet\System\Logging\ILogger;
use DevNet\System\Logging\ILoggerProvider;

class FileLoggerProvider implements ILoggerProvider
{
    private string $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function createLogger(string $category): ILogger
    {
        return new FileLogger($category, $this->fileName);
    }
}
