<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Parsing;

class Parser
{
    public const ERROR  = 0;
    public const GOTO   = 1;
    public const SHIFT  = 2;
    public const REDUCE = 3;
    public const ACCEPT = 4;

    public int $Action = 0;
    private int $reduceId = 0;
    private Stack $pointer;
    private Grammar $grammar;
    private Stack $state;
    private Stack $node;
    private int $position;

    public function __construct(Grammar $grammar)
    {
        $this->grammar = $grammar;
    }

    public function consume(string $input): void
    {
        $this->grammar->consume($input);
        $this->pointer  = new Stack();
        $this->state    = new Stack();
        $this->node     = new Stack();
        $this->Action   = 0;
        $this->reduceId = 0;
        $this->pointer->push(0);
        $this->state->push([]);
        $this->node->push(new Node('$S', []));
    }

    public function advance(): void
    {
        if ($this->reduceId == 1) {
            $this->Action = self::ACCEPT;
        } else {
            $itemName = $this->node->peek()->getName();
            $state = $this->state->peek();
            $position = $this->pointer->peek();
            $nextState = null;
            $state = $this->grammar->match($state, $position, $itemName);

            if ($state == true || $this->Action == self::ERROR) {
                $nextState = $this->grammar->lookAhead($itemName);
                if ($nextState == true && $nextState != $state) {
                    $this->Action = self::GOTO;
                } else {
                    $rule = $this->grammar->canReduce($state, $position, $itemName, $this->state, $this->pointer);
                    if ($rule) {
                        $this->Action = self::REDUCE;
                    } else {
                        $this->Action = self::SHIFT;
                    }
                }
            } else {
                $newState = $this->grammar->goTo($itemName);
                if ($newState) {
                    $this->Action = self::GOTO;
                } else {
                    $this->Action = self::ERROR;
                }
            }

            switch ($this->Action) {
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
                            $this->node->push(new Node($rule->Name, $item->getValues()));
                        } else {
                            $this->node->push(new Node($rule->Name, [$item]));
                        }
                    } else {
                        foreach ($items as &$item) {
                            $values = $item->getValues();
                            if (count($values) == 1) {
                                $item = $values[0];
                            }
                        }
                        $this->node->push(new Node($rule->Name, array_reverse($items)));
                    }

                    $this->state->pop();
                    $this->reduceId = $rule->Index;
                    break;
            }
        }
    }

    public function getAction(): int
    {
        return $this->Action;
    }

    public function getReduceId(): int
    {
        return $this->reduceId;
    }

    public function getNode(): ?Node
    {
        return $this->node->peek();
    }

    public function dump(): array
    {
        $dump = [
            'nodes'     => $this->node,
            'action'    => $this->Action,
            'position'  => $this->pointer->peek(),
            'reducedId' => $this->reduceId,
            //'state'     => $this->state->peek()
        ];

        return $dump;
    }
}
