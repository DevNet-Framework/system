<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DevNet\System\PropertyTrait;

class StackFrame
{
    use PropertyTrait;

    private ?string $fileName;
    private ?int $lineNumber;
    private ?string $className;
    private ?string $functionName;
    private array $arguments;

    public function __construct(array $frame)
    {
        $this->fileName     = $frame['file'] ?? null;
        $this->lineNumber   = $frame['line'] ?? null;
        $this->className    = $frame['class'] ?? null;
        $this->functionName = $frame['function'] ?? null;
        $this->arguments    = $frame['args'] ?? [];
    }

    public function get_FileName(): ?string
    {
        return $this->fileName;
    }

    public function get_LineNumber(): ?int
    {
        return $this->lineNumber;
    }

    public function get_ClassName(): ?string
    {
        return $this->className;
    }

    public function get_FunctionName(): ?string
    {
        return $this->functionName;
    }

    public function get_Arguments(): array
    {
        return $this->arguments;
    }
}
