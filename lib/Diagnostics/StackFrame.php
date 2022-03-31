<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DevNet\System\Exceptions\PropertyException;

class StackFrame
{
    private ?string $fileName;
    private ?int $lineNumber;
    private ?string $className;
    private ?string $functionName;
    private array $arguments;

    public function __get(string $name)
    {
        if (in_array($name, ['FileName', 'LineNumber', 'ClassName', 'FunctionName', 'Arguments'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
    }

    public function __construct(array $frame)
    {
        $this->fileName     = $frame['file'] ?? null;
        $this->lineNumber   = $frame['line'] ?? null;
        $this->className    = $frame['class'] ?? null;
        $this->functionName = $frame['function'] ?? null;
        $this->arguments    = $frame['args'] ?? [];
    }
}
