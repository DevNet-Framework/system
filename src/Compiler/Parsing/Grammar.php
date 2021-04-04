<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Parsing;

use DevNet\System\Compiler\Lexing\ILexer;

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

    public function consume(string $input) {
        $this->lexer->scan($input);
        $this->lexer->advance();
        $this->tokens = [$this->lexer->getToken()];
    }

    public function match($state, int $position, string $itemName)
    {
        $this->position = $position--;
        $this->itemName = $itemName;
        $this->state = $state;
        $matchedRules = [];
        foreach ($state as $rule) {
            if (isset($rule->predecates[$position])) {
                if ($rule->predecates[$position] == $itemName) {
                    $matchedRules[$rule->index] = $rule;
                }
            }
        }

        return $this->filterRules($matchedRules, $position + 1, 0);
    }

    public function lookAhead(string $item) {
        $nextRules = [];
        foreach ($this->rules as $rule) {
            if (isset($rule->predecates[0])) {
                if ($rule->predecates[0] == $item && count($rule->predecates) > 1) {
                    $nextRules[$rule->index] = $rule;
                }
            }
        }

        $token = $this->nextToken(0);
        $item = $token->getName();
        $matchedRules = [];
        foreach ($nextRules as $rule) {
            if (isset($rule->predecates[1])) {
                if ($rule->predecates[1] == $item) {
                    $matchedRules[$rule->index] = $rule;
                }
            }
        }

        return $this->filterRules($matchedRules, 2, 1);
    }

    public function goTo(string $itemName)
    {
        $matchedRules = [];
        foreach ($this->rules as $rule) {
            if (isset($rule->predecates[0])) {
                if ($rule->predecates[0] == $itemName) {
                    $matchedRules[$rule->index] = $rule;
                }
            }
        }

        return $this->filterRules($matchedRules, 1, 0);
    }

    public function filterRules(array $state, int $position, $lookAhead)
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
                    if (isset($rule->predecates[$position])) {
                        if ($rule->predecates[$position] == $itemName) {
                            $matchedRules[$rule->index] = $rule;
                        }
                    }
                }

                $matchedRules = $this->filterRules($matchedRules, $position + 1, $lookAhead + 1);
                if ($matchedRules) {
                    return $matchedRules;
                }

                foreach ($state as $rule) {
                    if (isset($rule->predecates[$position])) {
                        foreach ($this->rules as $ruleMap) {
                            if ($ruleMap->name == $rule->predecates[$position] && $ruleMap->predecates[0] != $rule->predecates[$position]) {
                                $derivationRules = [];
                                $derivationRules[$ruleMap->index] = $ruleMap;
                                $derivationRules = $this->derivation($derivationRules);
                                foreach ($derivationRules as $derivationRule) {
                                    if ($derivationRule->predecates[0] == $itemName) {
                                        $matchedRules[$rule->index] = $rule;
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

    public function derivation(array $rules)
    {
        if ($rules) {
            $matchedRules = [];
            foreach ($rules as $rule) {
                foreach ($this->rules as $ruleMap) {
                    if ($ruleMap->name == $rule->predecates[0] && $ruleMap->predecates[0] != $rule->predecates[0]) {
                        $matchedRules[$ruleMap->index] = $ruleMap;
                    }
                }
            }
            return $rules += $this->derivation($matchedRules);
        }

        return $rules;
    }

    public function canReduce($state, $position, $itemName, $stackState, $stackPointer)
    {
        $rules = [];
        $token = $this->nextToken(0);
        $nextItemName = $token->getName();

        foreach ($state as $rule) {
            $size = count($rule->predecates);
            if ($size == $position) {
                if (isset($rule->predecates[$size - 1])) {
                    if ($rule->predecates[$size - 1] == $itemName) {
                        $rules[$rule->index] = $rule;
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
                    if (isset($lastRule->predecates[$lastPosition - 1])) {
                        $lastRuleName = $lastRule->predecates[$lastPosition - 1];
                        foreach ($rules as $rule) {
                            if ($rule->name == $lastRuleName) {
                                return $rule;
                            }
                        }
                    }
                }
    
                return reset($rules);
                break;
        }
    }

    public function shift()
    {
        $token = array_shift($this->tokens);
        if ($token) {
            return $token;
        }

        $this->lexer->advance();
        $token = $this->lexer->getToken();
        return $token;
    }

    public function nextToken($level)
    {
        if (isset($this->tokens[$level])) {
            return $this->tokens[$level];
        }

        $this->lexer->advance();
        $token = $this->lexer->getToken();
        $this->tokens[] = $token;
        return $token;
    }

    public function next()
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

    public function getRule($ruleIndex)
    {
        if (isset($this->rules[$ruleIndex])) {
            return $this->rules[$ruleIndex];
        }

        return null;
    }
}
