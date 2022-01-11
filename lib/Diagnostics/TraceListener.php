<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

abstract class TraceListener
{
    protected int $IndentLevel = 0;
    protected int $IndentSize = 4;
    protected bool $NeedIndent = false;

    public function indent(): void
    {
        $this->IndentLevel++;
    }

    public function unindent(): void
    {
        $this->IndentLevel--;
    }

    public abstract function write($vakue, ?string $category): void;

    public abstract function writeLine($vakue, ?string $category): void;
}
