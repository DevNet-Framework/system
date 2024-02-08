<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DevNet\System\IO\Stream;

abstract class TraceListener
{
    protected Stream $Writer;
    protected bool $NeedIndent = false;
    protected int $IndentLevel = 0;
    public int $IndentSize = 4;

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
