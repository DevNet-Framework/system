<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Compiler\Parsing;

use Artister\System\Compiler\Lexing\ILexer;

class ParserBuilder
{
    private int $id = 0;
    private array $rules = [];
    private ILexer $lexer;

    public function __construct(ILexer $lexer)
    {
        $this->lexer = $lexer;
    }

    public function define(string $name, array $predicate) : int
    {
        $this->id++;
        $this->rules[$this->id] = new Rule($this->id, $name,  $predicate);
        return $this->id;
    }

    public function build() : Parser
    {
        return new Parser(new Grammar($this->lexer, $this->rules));
    }
}