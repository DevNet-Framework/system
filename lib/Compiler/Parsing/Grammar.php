<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Parsing;

use DevNet\System\Compiler\Lexing\ILexer;
use DevNet\System\Compiler\Lexing\IToken;

class Grammar
{
    private int $index = 0;
    private ILexer $lexer;
    private array $rules;
    private array $tokens;
    private string $itemName;
    private int $position;
    private array $state;

    public function __construct(ILexer $lexer, array $rules)
    {
        $this->lexer = $lexer;
        $this->rules = $rules;
    }

    public function consume(string $input): void
    {
        $this->lexer->scan($input);
        $this->lexer->advance();
        $this->tokens = [$this->lexer->getToken()];
    }

    public function match($state, int $position, string $itemName): array
    {
        $this->position = $position--;
        $this->itemName = $itemName;
        $this->state = $state;
        $matchedRules = [];
        foreach ($state as $rule) {
            if (isset($rule->Predicates[$position])) {
                if ($rule->Predicates[$position] == $itemName) {
                    $matchedRules[$rule->Index] = $rule;
                }
            }
        }

        return $this->filterRules($matchedRules, $position + 1, 0);
    }

    public function lookAhead(string $item): array
    {
        $nextRules = [];
        foreach ($this->rules as $rule) {
            if (isset($rule->Predicates[0])) {
                if ($rule->Predicates[0] == $item && count($rule->Predicates) > 1) {
                    $nextRules[$rule->Index] = $rule;
                }
            }
        }

        $token = $this->nextToken(0);
        $item = $token->getName();
        $matchedRules = [];
        foreach ($nextRules as $rule) {
            if (isset($rule->Predecates[1])) {
                if ($rule->Predecates[1] == $item) {
                    $matchedRules[$rule->Index] = $rule;
                }
            }
        }

        return $this->filterRules($matchedRules, 2, 1);
    }

    public function goTo(string $itemName): array
    {
        $matchedRules = [];
        foreach ($this->rules as $rule) {
            if (isset($rule->Predecates[0])) {
                if ($rule->Predecates[0] == $itemName) {
                    $matchedRules[$rule->Index] = $rule;
                }
            }
        }

        return $this->filterRules($matchedRules, 1, 0);
    }

    public function filterRules(array $state, int $position, $lookAhead): array
    {
        switch (count($state)) {
            case 0:
                return $state;
                break;
            case 1:
                return $state;
                break;
            default:
                $token = $this->nextToken($lookAhead);
                $itemName = $token->getName();
                $matchedRules = [];
                foreach ($state as $rule) {
                    if (isset($rule->Predecates[$position])) {
                        if ($rule->Predecates[$position] == $itemName) {
                            $matchedRules[$rule->Index] = $rule;
                        }
                    }
                }

                $matchedRules = $this->filterRules($matchedRules, $position + 1, $lookAhead + 1);
                if ($matchedRules) {
                    return $matchedRules;
                }

                foreach ($state as $rule) {
                    if (isset($rule->Predecates[$position])) {
                        foreach ($this->rules as $ruleMap) {
                            if ($ruleMap->Name == $rule->Predecates[$position] && $ruleMap->Predecates[0] != $rule->Predecates[$position]) {
                                $derivationRules = [];
                                $derivationRules[$ruleMap->Index] = $ruleMap;
                                $derivationRules = $this->derivation($derivationRules);
                                foreach ($derivationRules as $derivationRule) {
                                    if ($derivationRule->Predecates[0] == $itemName) {
                                        $matchedRules[$rule->Index] = $rule;
                                    }
                                }
                            }
                        }
                    }
                }

                if ($matchedRules) {
                    return $matchedRules;
                }

                return $state;
                break;
        }
    }

    public function derivation(array $rules): array
    {
        if ($rules) {
            $matchedRules = [];
            foreach ($rules as $rule) {
                foreach ($this->rules as $ruleMap) {
                    if ($ruleMap->Name == $rule->Predecates[0] && $ruleMap->Predecates[0] != $rule->Predecates[0]) {
                        $matchedRules[$ruleMap->Index] = $ruleMap;
                    }
                }
            }
            return $rules += $this->derivation($matchedRules);
        }

        return $rules;
    }

    public function canReduce($state, $position, $itemName, $stackState, $stackPointer): ?Rule
    {
        $rules = [];
        $token = $this->nextToken(0);
        $nextItemName = $token->getName();

        foreach ($state as $rule) {
            $size = count($rule->Predecates);
            if ($size == $position) {
                if (isset($rule->Predecates[$size - 1])) {
                    if ($rule->Predecates[$size - 1] == $itemName) {
                        $rules[$rule->Index] = $rule;
                    }
                }
            }
        }

        switch (count($rules)) {
            case 0:
                return null;
                break;
            case 1:
                return reset($rules);
                break;
            default:
                $stackState = clone $stackState;
                $stackPointer = clone $stackPointer;
                $stackState->pop();
                $stackPointer->pop();
                $lastState = $stackState->pop();
                $lastPosition = $stackPointer->pop();

                $lastRuleName = null;
                foreach ($lastState as $lastRule) {
                    if (isset($lastRule->Predecates[$lastPosition - 1])) {
                        $lastRuleName = $lastRule->Predecates[$lastPosition - 1];
                        foreach ($rules as $rule) {
                            if ($rule->Name == $lastRuleName) {
                                return $rule;
                            }
                        }
                    }
                }

                return reset($rules);
                break;
        }
    }

    public function shift(): IToken
    {
        $token = array_shift($this->tokens);
        if ($token) {
            return $token;
        }

        $this->lexer->advance();
        $token = $this->lexer->getToken();
        return $token;
    }

    public function nextToken($level): IToken
    {
        if (isset($this->tokens[$level])) {
            return $this->tokens[$level];
        }

        $this->lexer->advance();
        $token = $this->lexer->getToken();
        $this->tokens[] = $token;
        return $token;
    }

    public function next(): IToken
    {
        $token = reset($this->tokens);
        if ($token) {
            return $token;
        }

        $this->lexer->advance();
        $token = $this->lexer->getToken();
        $this->tokens[] = $token;
        return $token;
    }

    public function getRule($ruleIndex): ?Rule
    {
        if (isset($this->rules[$ruleIndex])) {
            return $this->rules[$ruleIndex];
        }

        return null;
    }
}
