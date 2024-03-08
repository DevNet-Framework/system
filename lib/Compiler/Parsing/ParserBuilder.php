<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Parsing;

use DevNet\System\Compiler\Lexing\ILexer;

class ParserBuilder
{
    private int $id = 0;
    private array $rules = [];
    private ILexer $lexer;

    public function __construct(ILexer $lexer)
    {
        $this->lexer = $lexer;
    }

    public function define(string $name, array $predicate): int
    {
        $this->id++;
        $this->rules[$this->id] = new Rule($this->id, $name,  $predicate);
        return $this->id;
    }

    public function build(): Parser
    {
        return new Parser(new Grammar($this->lexer, $this->rules));
    }
}
