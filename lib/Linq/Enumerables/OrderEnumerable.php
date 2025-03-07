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
use Closure;

class OrderEnumerable implements IEnumerable
{
    use MethodTrait;

    private IEnumerable $enumerable;
    private array $array = [];
    private array $sort  = [];

    public function __construct(IEnumerable $enumerable)
    {
        $this->enumerable = $enumerable;
    }

    public function orderBy(Closure $predicate): static
    {
        $array       = $this->enumerable->getIterator()->toArray();
        $this->sort  = $this->sort($array, $predicate);
        $list        = $this->list($this->sort);
        $this->array = $list;
        return $this;
    }

    public function orderByDescending(Closure $predicate): static
    {
        $array       = $this->enumerable->getIterator()->toArray();
        $this->sort  = $this->sort($array, $predicate, true);
        $list        = $this->list($this->sort);
        $this->array = $list;
        return $this;
    }

    public function thenBy(Closure $predicate): static
    {
        $map         = $this->sort($this->sort, $predicate);
        $list        = $this->list($map);
        $this->array = $list;
        return $this;
    }

    public function thenByDescending(Closure $predicate): static
    {
        $map         = $this->sort($this->sort, $predicate, true);
        $list        = $this->list($map);
        $this->array = $list;
        return $this;
    }

    private function sort(array $array, Closure $predicate, $reverseOrder = false): array
    {
        $sort = [];
        $leaf = false;
        foreach ($array as $key => $element) {
            $subKey = false;
            if (is_array($element)) {
                $element = $this->sort($element, $predicate, $reverseOrder);
            } else {
                $key    = $predicate($element);
                $subKey = true;
                $leaf   = true;
            }

            if ($subKey) {
                $sort[$key][] = $element;
            } else {
                $sort[$key] = $element;
            }
        }

        if ($leaf) {
            if ($reverseOrder) {
                krsort($sort);
            } else {
                ksort($sort);
            }
        }

        return $sort;
    }

    private function list(array $array): array
    {
        $list = [];
        foreach ($array as $element) {
            if (is_array($element)) {
                $element = $this->list($element);
                $list    = array_merge($list, $element);
            } else {
                $list[] = $element;
            }
        }

        return $list;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->array);
    }
}
