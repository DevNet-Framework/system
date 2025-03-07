<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

class StackFrame
{
    private ?string $fileName;
    private ?int $lineNumber;
    private ?string $className;
    private ?string $functionName;
    private array $arguments;

    public ?string $FileName { get => $this->fileName; }
    public ?int $LineNumber { get => $this->lineNumber; }
    public ?string $ClassName { get => $this->className; }
    public ?string $FunctionName { get => $this->functionName; }
    public array $Arguments { get => $this->arguments; }

    public function __construct(array $frame)
    {
        $this->fileName     = $frame['file'] ?? null;
        $this->lineNumber   = $frame['line'] ?? null;
        $this->className    = $frame['class'] ?? null;
        $this->functionName = $frame['function'] ?? null;
        $this->arguments    = $frame['args'] ?? [];
    }
}
