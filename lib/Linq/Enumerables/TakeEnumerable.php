<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Linq\Enumerables;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;
use DevNet\System\ObjectTrait;

class TakeEnumerable implements IEnumerable
{
    use ObjectTrait;

    private array $array = [];

    public function __construct(IEnumerable $enumerable)
    {
        $this->array = $enumerable->getIterator()->toArray();
    }

    public function take(int $limit): static
    {
        $i = 1;
        $elements = [];
        foreach ($this->array as $key => $element) {
            $elements[$key] = $element;

            if ($i == $limit) {
                break;
            }

            $i++;
        }

        $this->array = $elements;
        return $this;
    }

    public function skip(int $offset): static
    {
        $i = 1;
        $elements = [];
        foreach ($this->array as $key => $element) {
            if ($i <= $offset) {
                $i++;
                continue;
            }

            $elements[$key] = $element;
        }

        $this->array = $elements;
        return $this;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->array);
    }
}
