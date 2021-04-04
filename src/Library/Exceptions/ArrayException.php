<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Exceptions;

use Exception;

class ArrayException extends Exception
{
    public static function keyConstraint() : self
    {
        return new self("Key must be of the type Integert or String");
    }
    
    public static function invalidKeyType(string $requiredType) : self
    {
        return new self("Key must be of the type {$requiredType}");
    }

    public static function invalidValueType(string $requiredType) : self
    {
        return new self("Value must be of the type {$requiredType}");
    }
}
