<?php
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

interface LogLevel
{
    /**
     * Logs that contain the most detailed messages.
     */
    const Trace = 0;

    /**
     * Logs that are used for interactive investigation during development.
     */
    const Debug = 1;

    /**
     * Logs that track the general flow of the application.
     */
    const Notice = 2;

    /**
     * Logs that highlight the abnormal in the application flow, without stopping the execution.
     */
    const Warning = 3;

    /**
     * Logs that highlight when the current flow of execution is stopped due to a failure.
     */
    const Error = 4;

    /**
     * Logs that describe a system crash, or a catastrophic failure that requires immediate attention.
     */
    const Fatal = 5;

    /**
     * Logs that not used for writing log messages.
     */
    const None = 6;

}
