<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DevNet\System\IO\Stream;

abstract class TraceListener
{
    protected Stream $Writer;
    protected int $IndentLevel = 0;
    protected int $IndentSize = 4;
    protected bool $NeedIndent = false;

    public function __construct(Stream $writer)
    {
        $this->Writer = $writer;
    }

    public function indent(): void
    {
        $this->IndentLevel++;
    }

    public function unindent(): void
    {
        $this->IndentLevel--;
    }

    public function flush(): void
    {
        $this->Writer->flush();
    }

    public abstract function write($value, ?string $category): void;

    public abstract function writeLine($value, ?string $category): void;

    public abstract function caller(int $skipFrames): void;
}
