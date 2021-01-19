<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Compiler\Parsing;

class Parser
{
    const ERROR     = 0;
    const GOTO      = 1;
    const SHIFT     = 2;
    const REDUCE    = 3;
    const ACCEPT    = 4;

    public int $action = 0;

    private Grammar $grammar;
    public Stack $pointer;
    private Stack $state;
    private Stack $node;
    public int $reduceId = 0;
    private int $position;

    public function __construct(Grammar $grammar)
    {
        $this->grammar  = $grammar;
    }

    public function consume(string $input) {
        $this->grammar->consume($input);
        $this->pointer  = new Stack();
        $this->state    = new Stack();
        $this->node     = new Stack();
        $this->action = 0;
        $this->reduceId = 0;
        $this->pointer->push(0);
        $this->state->push([]);
        $this->node->push(new Node('$S', []));
    }

    public function advance()
    {
        if ($this->reduceId == 1) {
            $this->action = self::ACCEPT;
        } else {
            $itemName = $this->node->peek()->getName();
            $state = $this->state->peek();
            $position = $this->pointer->peek();
            $nextState = null;
            $state = $this->grammar->match($state, $position, $itemName);

            if($state == true || $this->action == self::ERROR) {
                $nextState = $this->grammar->lookAhead($itemName);
                if ($nextState == true && $nextState != $state) {
                    $this->action = self::GOTO;
                } else {
                    $rule = $this->grammar->canReduce($state, $position, $itemName, $this->state, $this->pointer);
                    if ($rule) {
                            $this->action = self::REDUCE;
                    } else {
                        $this->action = self::SHIFT;
                    }
                }
            } else {
                $newState = $this->grammar->goTo($itemName);
                if ($newState) {
                    $this->action = self::GOTO;
                } else {
                    $this->action = self::ERROR;
                }
            }

            switch ($this->action) {
                case self::GOTO:
                    if ($nextState) {
                        $this->pointer->push(1);
                        $this->state->push($nextState);
                    } else {
                        $this->pointer->push(1);
                        $this->state->push($newState);
                    }
                    break;
                case self::SHIFT:
                    $position = $this->pointer->pop();
                    $this->pointer->push($position + 1);
                    $token = $this->grammar->shift();
                    $this->node->push(new Node($token->getName(), [$token]));
                    break;
                case self::REDUCE:
                    $size = $this->pointer->pop();
                    $items = [];
                    for ($i = 0; $i < $size; $i++) {
                        $items[] = $this->node->pop();
                    }
                    if (count($items) == 1) {
                        $item = $items[0];
                        if (count($item->getValues()) == 1) {
                            $this->node->push(new Node($rule->name, $item->getValues()));
                        } else {
                            $this->node->push(new Node($rule->name, [$item]));
                        }
                    } else {
                        foreach ($items as &$item) {
                            $values = $item->getValues();
                            if (count($values) == 1) {
                                $item = $values[0];
                            }
                        }
                        $this->node->push(new Node($rule->name, array_reverse($items)));
                    }
                    
                    $this->state->pop();
                    $this->reduceId = $rule->index;
                    break;
            }
        }
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getReduceId()
    {
        return $this->reduceId;
    }

    public function getNode()
    {
        return $this->node->peek();
    }

    public function dump()
    {
        $dump = [
            'nodes'     => $this->node,
            'action'    => $this->action,
            'position'  => $this->pointer->peek(),
            'reducedId'    => $this->reduceId,
            //'state'     => $this->state->peek()
        ];

        return $dump;
    }
}