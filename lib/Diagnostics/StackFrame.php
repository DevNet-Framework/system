<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

class StackFrame
{
    private ?string $FileName;
    private ?int $LineNumber;
    private ?string $ClassName;
    private ?string $FunctionName;
    private array $Arguments;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(array $frame)
    {
        $this->FileName     = $frame['file'] ?? null;
        $this->LineNumber   = $frame['line'] ?? null;
        $this->ClassName    = $frame['class'] ?? null;
        $this->FunctionName = $frame['function'] ?? null;
        $this->Arguments    = $frame['args'] ?? [];
    }
}
