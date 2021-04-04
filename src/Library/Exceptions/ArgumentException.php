<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Exceptions;

use Exception;

class ArgumentException extends Exception
{
    public static function invalidArgumentType(string $className, string $methodName, int $argumentPosition, string $requiredType) : self
    {
        return new self("Argument {$argumentPosition} passed to {$className}::{$methodName}() must be of the type {$requiredType}");
    }
}
