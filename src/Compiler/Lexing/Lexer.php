<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Compiler\Lexing;

class Lexer implements ILexer
{
    private array $definitions;
    private string $input;
    private ?IToken $token;
    private int $offset = 0;

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    public function scan(string $input)
    {
        $this->input = $input;
        $this->token = null;
    }

    public function advance()
    {
        foreach ($this->definitions as $definition) {
            if ($this->input == '') {
                $this->token = new Token(Token::EOI);
                break;
            } else if (preg_match('%^('.$definition->pattern.')%', $this->input, $matches)) {
                $tokenValue = $matches[0];
                $tokenLength = strlen($tokenValue);
                $this->offset += $tokenLength;
                $this->input = substr($this->input, $tokenLength);
                $this->token = new Token($definition->name, $tokenValue);
                if ($definition->name == Token::SKIPPED) {
                    $this->advance();
                }
                break;
            }
        }
    }

    public function getToken() : IToken
    {
        return $this->token;
    }

    public function reset()
    {
        $this->definition = [];
        $this->input = '';
        $this->token = null;
    }

    public function getTokens()
    {
        $position = 0;
        $tokens = [];
        while (strlen($this->input)) {
            $matches = null;
            foreach ($this->definitions as $definition) {
                if (preg_match('%^'.$definition->pattern.'%', $this->input, $matches)) {
                    $tokenValue = $matches[0];
                    $tokenLength = strlen($tokenValue);
                    $this->input = substr($this->input, $tokenLength);
                    if ($definition->type != -1) {
                        $tokens[] = new Token($tokenValue, $definition->type);
                        ++$position;
                    }
                    break;
                }
            }
            if (!$matches) {
                throw new \Exception(sprintf('At offset %s: %s', $this->offset, substr($this->input, 0, 16).'...'));
            }
        }
        return $tokens;
    }
}