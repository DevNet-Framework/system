<?php
/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging;

interface ILogger
{
    /**
     * Writes a log entry.
     */
    public function log(LogLevel $level, string $message, array $args): void;
}
