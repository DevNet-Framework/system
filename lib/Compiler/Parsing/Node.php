<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Parsing;

use DevNet\System\Compiler\IComponent;
use IteratorAggregate;
use Traversable;

class Node implements IComponent, IteratorAggregate
{
    public string $Name;
    public array $Values = [];

    public function __construct(string $name, array $values)
    {
        $this->Name = $name;
        $this->Values = $values;
    }

    public function add(Node $node): void
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

    public function getIterator(): Traversable
    {
        foreach ($this->Values as $value) {
            yield $value;
        }
    }
}
