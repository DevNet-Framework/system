<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Exceptions;

use Exception;

class MethodException extends Exception
{
    public static function undefinedMethod(string $className, string $methodName): self
    {
        return new self("Call to undefined method {$className}::{$methodName}()");
    }

    public static function privateMethod(string $className, string $methodName): self
    {
        return new self("Call to private method {$className}::{$methodName}()");
    }

    public static function protectedMethod(string $className, string $methodName): self
    {
        return new self("Call to protected method {$className}::{$methodName}()");
    }

    public static function invalidReturnType(string $className, string $methodName, string $requiredType): self
    {
        return new self("Return value of {$className}::{$methodName}()} must be of the type {$requiredType}");
    }
}
