<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Exceptions;

use Exception;

class ClassException extends Exception
{
    public static function classNotFound(string $className): self
    {
        return new self("Class {$className} not found");
    }
}
