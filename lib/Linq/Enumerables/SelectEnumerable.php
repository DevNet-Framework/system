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
use Closure;

class SelectEnumerable implements IEnumerable
{
    use \DevNet\System\Extension\ExtenderTrait;

    private IEnumerable $enumerable;
    private array $array = [];

    public function __construct(IEnumerable $enumerable)
    {
        $this->enumerable = $enumerable;
    }

    public function select(Closure $predecate)
    {
        $elements = [];
        foreach ($this->enumerable as $element) {
            $object = $predecate($element);
            $elements[] = $object;
        }

        $this->array = $elements;
        return $this;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->array);
    }
}
