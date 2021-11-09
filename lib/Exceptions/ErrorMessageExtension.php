<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Exceptions;

use DevNet\System\Text\StringBuilder;

class ErrorMessageExtension
{
    public static function invalidPropertType(
        StringBuilder $builder,
        string $className,
        string $propertyName,
        string $requiredType
    ): void {
        $builder->append(
            "Property {$className}::{$propertyName} must be of the type {$requiredType}"
        );
    }

    public static function invalidArgumentType(
        StringBuilder $builder,
        string $className,
        string $methodName,
        int $argumentPosition,
        string $requiredType
    ): void {
        $builder->append(
            "Argument {$argumentPosition} passed to {$className}::{$methodName}() must be of the type {$requiredType}"
        );
    }

    public static function invalidReturnType(
        StringBuilder $builder,
        string $className,
        string $methodName,
        string $requiredType
    ): void {
        $builder->append(
            "Return Type of {$className}::{$methodName}() must be of the type {$requiredType}"
        );
    }

    public static function invalidKeyType(
        StringBuilder $builder,
        string $targetName,
        string $requiredType
    ): void {
        $builder->append(
            "Key passed to {$targetName} must be of the type {$requiredType}"
        );
    }
}
