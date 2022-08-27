<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
                $this->file = $trace[$scope - 1]['file'];
                $this->line = $trace[$scope - 1]['line'];
            }
        }
    }
}
