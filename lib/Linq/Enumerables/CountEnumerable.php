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
use DevNet\System\PropertyTrait;
use Closure;

class CountEnumerable implements IEnumerable
{
    use PropertyTrait;

    private IEnumerable $enumerable;

    public function __construct(IEnumerable $enumerable)
    {
        $this->enumerable = $enumerable;
    }

    public function count(Closure $predecate = null): int
    {
        $cout = 0;
        foreach ($this->enumerable as $element) {
            if ($predecate) {
                if ($predecate($element)) {
                    $cout++;
                }
            } else {
                $cout++;
            }
        }

        return $cout;
    }

    public function max(Closure $predecate = null)
    {
        $value = null;
        foreach ($this->enumerable as $element) {
            if ($predecate) {
                $element = $predecate($element);
            }

            if ($value == null || $element > $value) {
                $value = $element;
            }
        }

        return $value;
    }

    public function min(Closure $predecate = null)
    {
        $value = null;
        foreach ($this->enumerable as $element) {
            if ($predecate) {
                $element = $predecate($element);
            }

            if ($value == null || $element < $value) {
                $value = $element;
            }
        }

        return $value;
    }

    public function getIterator(): Enumerator
    {
        return $this->enumerable->getIterator();
    }
}
