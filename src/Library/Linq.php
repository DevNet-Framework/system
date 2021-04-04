<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Collections\IEnumerable;
use DevNet\System\Linq\Enumerable;
use DevNet\System\Linq\IQueryable;
use DevNet\System\Linq\Queryable;
use Closure;

class Linq
{
    public static function select(IEnumerable $enumerable, Closure $predecate)
    {
        return Enumerable::select($enumerable, $predecate);
    }

    public static function where(IEnumerable $enumerable, Closure $predecate)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            return Queryable::where($enumerable, $predecate);
        }

        return Enumerable::where($enumerable, $predecate);
    }

    public static function orderBy(IEnumerable $enumerable, Closure $predecate)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            return Queryable::orderBy($enumerable, $predecate);
        }

        return Enumerable::orderBy($enumerable, $predecate);
    }
    
    public static function orderByDescending(IEnumerable $enumerable, Closure $predecate)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            return Queryable::orderByDescending($enumerable, $predecate);
        }

        return Enumerable::orderByDescending($enumerable, $predecate);
    }

    public static function skip(IEnumerable $enumerable, int $offset)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            return Queryable::skip($enumerable, $offset);
        }

        return Enumerable::skip($enumerable, $offset);
    }

    public static function take(IEnumerable $enumerable, int $limit)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            return Queryable::take($enumerable, $limit);
        }

        return Enumerable::take($enumerable, $limit);
    }

    public static function groupBy(IEnumerable $enumerable, Closure $predecate)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            throw new \Exception("Queriable method not implemented yet, try it as Enumerable");
        }

        return Enumerable::groupBy($enumerable, $predecate);
    }

    public static function join(IEnumerable $enumerable, $innerCollection, Closure $outerSelector, Closure $innerSelector, Closure $resultSelector)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            throw new \Exception("Queriable method not implemented yet, try it as Enumerable");
        }

        return Enumerable::join($enumerable, $innerCollection, $outerSelector, $innerSelector, $resultSelector);
    }

    public static function count(IEnumerable $enumerable, Closure $predecate = null) : int
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            throw new \Exception("Queriable method not implemented yet, try it as Enumerable");
        }

        return Enumerable::count($enumerable, $predecate);
    }

    public static function max(IEnumerable $enumerable, Closure $predecate = null)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            throw new \Exception("Queriable method not implemented yet, try it as Enumerable");
        }

        return Enumerable::max($enumerable, $predecate);
    }

    public static function min(IEnumerable $enumerable, Closure $predecate = null)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            throw new \Exception("Queriable method not implemented yet, try it as Enumerable");
        }

        return Enumerable::min($enumerable, $predecate);
    }

    public static function first(IEnumerable $enumerable)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            return Queryable::first($enumerable);
        }

        return Enumerable::first($enumerable);
    }

    public static function last(IEnumerable $enumerable)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces))
        {
            return Queryable::last($enumerable);
        }

        return Enumerable::last($enumerable);
    }
}
