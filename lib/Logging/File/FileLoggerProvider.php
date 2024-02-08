<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
