<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
    public static function select(IEnumerable $enumerable, Closure $predicate): IEnumerable
    {
        return Enumerable::select($enumerable, $predicate);
    }

    public static function where(IEnumerable $enumerable, Closure $predicate): IEnumerable
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            return Queryable::where($enumerable, $predicate);
        }

        return Enumerable::where($enumerable, $predicate);
    }

    public static function orderBy(IEnumerable $enumerable, Closure $predicate): IEnumerable
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            return Queryable::orderBy($enumerable, $predicate);
        }

        return Enumerable::orderBy($enumerable, $predicate);
    }

    public static function orderByDescending(IEnumerable $enumerable, Closure $predicate): IEnumerable
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            return Queryable::orderByDescending($enumerable, $predicate);
        }

        return Enumerable::orderByDescending($enumerable, $predicate);
    }

    public static function skip(IEnumerable $enumerable, int $offset): IEnumerable
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            return Queryable::skip($enumerable, $offset);
        }

        return Enumerable::skip($enumerable, $offset);
    }

    public static function take(IEnumerable $enumerable, int $limit): IEnumerable
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            return Queryable::take($enumerable, $limit);
        }

        return Enumerable::take($enumerable, $limit);
    }

    public static function groupBy(IEnumerable $enumerable, Closure $predicate): IEnumerable
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            throw new \Exception("Queryable method not implemented yet, try it as Enumerable");
        }

        return Enumerable::groupBy($enumerable, $predicate);
    }

    public static function join(IEnumerable $enumerable, $innerCollection, Closure $outerSelector, Closure $innerSelector, Closure $resultSelector): IEnumerable
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            throw new \Exception("Queryable method not implemented yet, try it as Enumerable");
        }

        return Enumerable::join($enumerable, $innerCollection, $outerSelector, $innerSelector, $resultSelector);
    }

    public static function count(IEnumerable $enumerable, ?Closure $predicate = null): int
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            throw new \Exception("Queryable method not implemented yet, try it as Enumerable");
        }

        return Enumerable::count($enumerable, $predicate);
    }

    public static function max(IEnumerable $enumerable, ?Closure $predicate = null)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            throw new \Exception("Queryable method not implemented yet, try it as Enumerable");
        }

        return Enumerable::max($enumerable, $predicate);
    }

    public static function min(IEnumerable $enumerable, ?Closure $predicate = null)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            throw new \Exception("Queryable method not implemented yet, try it as Enumerable");
        }

        return Enumerable::min($enumerable, $predicate);
    }

    public static function first(IEnumerable $enumerable)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            return Queryable::first($enumerable);
        }

        return Enumerable::first($enumerable);
    }

    public static function last(IEnumerable $enumerable)
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            return Queryable::last($enumerable);
        }

        return Enumerable::last($enumerable);
    }

    public static function toArray(IEnumerable $enumerable): array
    {
        $interfaces = class_implements($enumerable);
        if (in_array(IQueryable::class, $interfaces)) {
            return Queryable::toArray($enumerable);
        }

        return Enumerable::toArray($enumerable);
    }
}
