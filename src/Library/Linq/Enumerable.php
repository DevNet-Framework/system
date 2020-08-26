<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Linq;

use Artister\System\Collections\IEnumerable;
use Artister\System\Linq\Enumerables\GroupEnumerable;
use Artister\System\Linq\Enumerables\JoinEnumerable;
use Artister\System\Linq\Enumerables\OrderEnumerable;
use Artister\System\Linq\Enumerables\SelectEnumerable;
use Artister\System\Linq\Enumerables\WhereEnumerable;
use Artister\System\Linq\Enumerables\CountEnumerable;
use Artister\System\Linq\Enumerables\TakeEnumerable;
use Closure;

class Enumerable
{
    public static function select(IEnumerable $enumerable, Closure $predecate)
    {
        $selectEnumerable = new SelectEnumerable($enumerable);
        return $selectEnumerable->select($predecate);
    }

    public static function where(IEnumerable $enumerable, Closure $predecate)
    {
        $whereEnumerable = new WhereEnumerable($enumerable);
        return $whereEnumerable->where($predecate);
    }

    public static function orderBy(IEnumerable $enumerable, Closure $predecate)
    {
        $orderEnumerable = new OrderEnumerable($enumerable);
        return $orderEnumerable->orderBy($predecate);
    }
    
    public static function orderByDescending(IEnumerable $enumerable, Closure $predecate)
    {
        $orderEnumerable = new OrderEnumerable($enumerable);
        return $orderEnumerable->orderByDescending($predecate);
    }

    public static function groupBy(IEnumerable $enumerable, Closure $predecate)
    {
        $group = new GroupEnumerable($enumerable);
        return $group->groupBy($predecate);
    }

    public static function join(IEnumerable $enumerable, $innerCollection, Closure $outerSelector, Closure $innerSelector, Closure $resultSelector)
    {
        $joined = new JoinEnumerable($enumerable);
        return $joined->join($innerCollection, $outerSelector, $innerSelector, $resultSelector);
    }

    public static function count(IEnumerable $enumerable, Closure $predecate = null) : int
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

    public static function take(IEnumerable $enumerable, int $limit)
    {
        $take = new TakeEnumerable($enumerable);
        return $take->take($limit);
    }

    public static function skip(IEnumerable $enumerable, int $offset)
    {
        $take = new TakeEnumerable($enumerable);
        return $take->skip($offset);
    }

    public static function first(IEnumerable $enumerable)
    {
        $take = new TakeEnumerable($enumerable);
        return $take->first();
    }

    public static function last(IEnumerable $enumerable)
    {
        $take = new TakeEnumerable($enumerable);
        return $take->last();
    }
}