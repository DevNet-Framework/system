<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Lexing;

class LexerBuilder
{
    private array $definitions;

    public function define(string $name, string $pattern): void
    {
        $this->definitions[] = new TokenDefenition($name, $pattern);
    }

    public function build(): Lexer
    {
        $this->definitions[] = new TokenDefenition(Token::SKIPPED, "\s+");
        $this->definitions[] = new TokenDefenition(Token::UNKNOWN, "[^\s]+");
        return new Lexer($this->definitions);
    }
}
