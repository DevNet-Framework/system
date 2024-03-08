<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\MethodTrait;
use DevNet\System\Template;
use DevNet\System\Type;
use DevNet\System\TypeTrait;

#[Template('T')]
class Queue implements IEnumerable
{
    use MethodTrait;
    use TypeTrait;

    private array $array = [];

    public function __construct(string $valueType)
    {
        $this->setGenericArguments($valueType);
    }

    public function enqueue(#[Type('T')] $value): void
    {
        $this->checkArgumentTypes(func_get_args());
        $this->array[] = $value;
    }

    public function dequeue()
    {
        return array_shift($this->array);
    }

    public function peek()
    {
        return reset($this->array);
    }

    public function contains(#[Type('T')] $item): bool
    {
        $this->checkArgumentTypes(func_get_args());
        return in_array($item, $this->array);
    }

    public function remove(#[Type('T')] $item): void
    {
        $this->checkArgumentTypes(func_get_args());
        if (isset($this->array[$item])) {
            unset($this->array[$item]);
        }
    }

    public function clear(): void
    {
        $this->array = [];
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->array);
    }

    public function toArray(): array
    {
        return $this->array;
    }
}
