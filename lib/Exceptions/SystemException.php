<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Exceptions;

use Exception;
use Throwable;

class SystemException extends Exception
{
    public function __construct(string $message = "", int $code = 0, int $scope = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if ($scope > 0) {
            $trace = $this->getTrace();
            if (isset($trace[$scope - 1])) {
                if (isset($trace[$scope - 1]['file'])) {
                    $this->file = $trace[$scope - 1]['file'];
                }

                if (isset($trace[$scope - 1]['line'])) {
                    $this->line = $trace[$scope - 1]['line'];
                }
            }
        }
    }
}
