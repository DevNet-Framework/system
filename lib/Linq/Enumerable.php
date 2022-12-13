<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Linq;

use DevNet\System\Collections\IEnumerable;
use DevNet\System\Linq\Enumerables\GroupEnumerable;
use DevNet\System\Linq\Enumerables\JoinEnumerable;
use DevNet\System\Linq\Enumerables\OrderEnumerable;
use DevNet\System\Linq\Enumerables\SelectEnumerable;
use DevNet\System\Linq\Enumerables\WhereEnumerable;
use DevNet\System\Linq\Enumerables\CountEnumerable;
use DevNet\System\Linq\Enumerables\TakeEnumerable;
use Closure;

abstract class Enumerable
{
    public static function select(IEnumerable $enumerable, Closure $predecate): SelectEnumerable
    {
        $selectEnumerable = new SelectEnumerable($enumerable);
        return $selectEnumerable->select($predecate);
    }

    public static function where(IEnumerable $enumerable, Closure $predecate): WhereEnumerable
    {
        $whereEnumerable = new WhereEnumerable($enumerable);
        return $whereEnumerable->where($predecate);
    }

    public static function orderBy(IEnumerable $enumerable, Closure $predecate): OrderEnumerable
    {
        $orderEnumerable = new OrderEnumerable($enumerable);
        return $orderEnumerable->orderBy($predecate);
    }

    public static function orderByDescending(IEnumerable $enumerable, Closure $predecate): OrderEnumerable
    {
        $orderEnumerable = new OrderEnumerable($enumerable);
        return $orderEnumerable->orderByDescending($predecate);
    }

    public static function groupBy(IEnumerable $enumerable, Closure $predecate): GroupEnumerable
    {
        $group = new GroupEnumerable($enumerable);
        return $group->groupBy($predecate);
    }

    public static function join(IEnumerable $enumerable, $innerCollection, Closure $outerSelector, Closure $innerSelector, Closure $resultSelector): JoinEnumerable
    {
        $joined = new JoinEnumerable($enumerable);
        return $joined->join($innerCollection, $outerSelector, $innerSelector, $resultSelector);
    }

    public static function take(IEnumerable $enumerable, int $limit): TakeEnumerable
    {
        $take = new TakeEnumerable($enumerable);
        return $take->take($limit);
    }

    public static function skip(IEnumerable $enumerable, int $offset): TakeEnumerable
    {
        $take = new TakeEnumerable($enumerable);
        return $take->skip($offset);
    }

    public static function count(IEnumerable $enumerable, Closure $predecate = null): int
    {
        $count = new CountEnumerable($enumerable);
        return $count->count($predecate);
    }

    public static function max(IEnumerable $enumerable, Closure $predecate = null)
    {
        $count = new CountEnumerable($enumerable);
        return $count->max($predecate);
    }

    public static function min(IEnumerable $enumerable, Closure $predecate = null)
    {
        $count = new CountEnumerable($enumerable);
        return $count->min($predecate);
    }

    public static function first(IEnumerable $enumerable)
    {
        $array   = $enumerable->getIterator()->toArray();
        $element = reset($array);

        return $element ? $element : null;
    }

    public static function last(IEnumerable $enumerable)
    {
        $array   = $enumerable->getIterator()->toArray();
        $element = end($array);

        return $element ? $element : null;
    }

    public static function toArray(IEnumerable $enumerable): array
    {
        return $enumerable->getIterator()->toArray();
    }
}
