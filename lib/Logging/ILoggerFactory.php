<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

interface ILoggerFactory
{
    public function addProvider(ILoggerProvider $provider): void;

    public function createLogger(string $category): ILogger;
}
