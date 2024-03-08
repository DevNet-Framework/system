<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Linq\Enumerables;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;
use DevNet\System\MethodTrait;
use DevNet\System\PropertyTrait;
use Closure;

class CountEnumerable implements IEnumerable
{
    use MethodTrait;
    use PropertyTrait;

    private IEnumerable $enumerable;

    public function __construct(IEnumerable $enumerable)
    {
        $this->enumerable = $enumerable;
    }

    public function count(Closure $predicate = null): int
    {
        $count = 0;
        foreach ($this->enumerable as $element) {
            if ($predicate) {
                if ($predicate($element)) {
                    $count++;
                }
            } else {
                $count++;
            }
        }

        return $count;
    }

    public function max(Closure $predicate = null)
    {
        $value = null;
        foreach ($this->enumerable as $element) {
            if ($predicate) {
                $element = $predicate($element);
            }

            if ($value == null || $element > $value) {
                $value = $element;
            }
        }

        return $value;
    }

    public function min(Closure $predicate = null)
    {
        $value = null;
        foreach ($this->enumerable as $element) {
            if ($predicate) {
                $element = $predicate($element);
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
