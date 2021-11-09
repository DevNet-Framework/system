<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Exceptions;

use Exception;

class PropertyException extends Exception
{
    public static function undefinedPropery(string $ClassName, string $propertyName): self
    {
        return new self("access to undefined property {$ClassName}::{$propertyName}");
    }

    public static function privateProperty(string $ClassName, string $propertyName): self
    {
        return new self("access to private property {$ClassName}::{$propertyName}");
    }

    public static function protectedProperty(string $ClassName, string $propertyName): self
    {
        return new self("access to protected property {$ClassName}::{$propertyName}");
    }

    public static function invalidValueType(string $className, string $methodName, string $requiredType): self
    {
        return new self("Value passed to {$className}::{$methodName} must be of the type {$requiredType}");
    }
}
