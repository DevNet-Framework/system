<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Parsing;

use DevNet\System\Compiler\IComponent;
use IteratorAggregate;

class Node implements IComponent
{
    public string $Name;
    public array $Values = [];

    public function __construct(string $name, array $values)
    {
        $this->Name = $name;
        $this->Values = $values;
    }

    public function add(Node $node)
    {
        $this->Values[] = $node;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function getValue(int $index)
    {
        if ($this->Values[$index]) {
            return $this->Values[$index];
        }
    }

    public function getValues(): array
    {
        return $this->Values;
    }

    public function getType(): string
    {
        return get_class($this);;
    }

    public function getIterator()
    {
        foreach ($this->Values as $value) {
            yield $value;
        }
    }
}
