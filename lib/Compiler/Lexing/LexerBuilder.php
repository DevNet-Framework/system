<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Lexing;

class LexerBuilder
{
    private array $definitions;

    public function define(string $name, string $pattern): void
    {
        $this->definitions[] = new TokenDefinition($name, $pattern);
    }

    public function build(): Lexer
    {
        $this->definitions[] = new TokenDefinition(Token::SKIPPED, "\s+");
        $this->definitions[] = new TokenDefinition(Token::UNKNOWN, "[^\s]+");
        return new Lexer($this->definitions);
    }
}
