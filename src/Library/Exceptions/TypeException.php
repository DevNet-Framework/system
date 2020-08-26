<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Exceptions;

use Artister\System\Type;
use Exception;

class TypeException extends Exception
{
    public static function invalidPropertType(string $className, string $propertyName, Type $requiredType) : self
    {
        return new self("Property {$className}::{$propertyName} must be of the type {$requiredType->Name}");
    }

    public static function invalidArgumentType(string $className, string $methodName, int $argumentPosition, Type $requiredType) : self
    {
        return new self("Argument {$argumentPosition} passed to {$className}::{$methodName}() must be of the type {$requiredType->Name}");
    }

    public static function invalidReturnType(string $className, string $methodName, Type $requiredType) : self
    {
        return new self("Return Type of {$className}::{$methodName}() must be of the type {$requiredType->Name}");
    }

    public static function invalidKeyType(string $targetName, Type $requiredType) : self
    {
        return new self("Key passed to {$targetName} must be of the type {$requiredType->Name}");
    }
}