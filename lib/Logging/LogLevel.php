<?php
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

enum LogLevel: int
{
    /**
     * Logs that contain the most detailed messages.
     */
    case Trace = 0;

    /**
     * Logs that are used for interactive investigation during development.
     */
    case Debug = 1;

    /**
     * Logs that track the general flow of the application.
     */
    case Information = 2;

    /**
     * Logs that highlight the abnormal in the application flow, without stopping the execution.
     */
    case Warning = 3;

    /**
     * Logs that highlight when the current flow of execution is stopped due to a failure.
     */
    case Error = 4;

    /**
     * Logs that describe a system crash, or a catastrophic failure that requires immediate attention.
     */
    case Fatal = 5;

    /**
     * Logs that not used for writing log messages.
     */
    case None = 6;

}
