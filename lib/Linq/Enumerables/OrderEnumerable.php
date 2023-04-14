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

    public function orderBy(Closure $predecate): static
    {
        $array       = $this->enumerable->getIterator()->toArray();
        $this->sort  = $this->sort($array, $predecate);
        $list        = $this->list($this->sort);
        $this->array = $list;
        return $this;
    }

    public function orderByDescending(Closure $predecate): static
    {
        $array       = $this->enumerable->getIterator()->toArray();
        $this->sort  = $this->sort($array, $predecate, true);
        $list        = $this->list($this->sort);
        $this->array = $list;
        return $this;
    }

    public function thenBy(Closure $predecate): static
    {
        $map         = $this->sort($this->sort, $predecate);
        $list        = $this->list($map);
        $this->array = $list;
        return $this;
    }

    public function thenByDescending(Closure $predecate): static
    {
        $map         = $this->sort($this->sort, $predecate, true);
        $list        = $this->list($map);
        $this->array = $list;
        return $this;
    }

    private function sort(array $array, Closure $predecate, $reverseOrder = false): array
    {
        $sort = [];
        $leaf = false;
        foreach ($array as $key => $element) {
            $subKey = false;
            if (is_array($element)) {
                $element = $this->sort($element, $predecate, $reverseOrder);
            } else {
                $key    = $predecate($element);
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
