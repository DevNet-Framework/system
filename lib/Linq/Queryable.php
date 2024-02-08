<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Linq;

use DevNet\System\Linq\IQueryable;
use DevNet\System\Compiler\Expressions\Expression;
use Closure;

abstract class Queryable
{
    public static function where(IQueryable $queryable, Closure $predicate): IQueryable
    {
        $lambda = Expression::Lambda($predicate);
        $expression = Expression::call(null, 'Where', [$queryable->Expression, $lambda]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function orderBy(IQueryable $queryable, Closure $predicate): IQueryable
    {
        $lambda = Expression::Lambda($predicate);
        $expression = Expression::call(null, 'OrderBy', [$queryable->Expression, $lambda]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function orderByDescending(IQueryable $queryable, Closure $predicate): IQueryable
    {
        $lambda = Expression::Lambda($predicate);
        $expression = Expression::call(null, 'OrderByDescending', [$queryable->Expression, $lambda]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function thenBy(IQueryable $queryable, Closure $predicate): IQueryable
    {
        $lambda = Expression::Lambda($predicate);
        $expression = Expression::call(null, 'ThenBy', [$queryable->Expression, $lambda]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function thenByDescending(IQueryable $queryable, Closure $predicate): IQueryable
    {
        $lambda = Expression::Lambda($predicate);
        $expression = Expression::call(null, 'ThenByDescending', [$queryable->Expression, $lambda]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function skip(IQueryable $queryable, int $offset): IQueryable
    {
        $constant = Expression::constant($offset);
        $expression = Expression::call(null, 'skip', [$queryable->Expression, $constant]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function take(IQueryable $queryable, int $limit): IQueryable
    {
        $constant = Expression::constant($limit);
        $expression = Expression::call(null, 'take', [$queryable->Expression, $constant]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function first(IQueryable $queryable)
    {
        $array   = $queryable->getIterator()->toArray();
        $element = reset($array);

        return $element ? $element : null;
    }

    public static function last(IQueryable $queryable)
    {
        $array   = $queryable->getIterator()->toArray();
        $element = end($array);

        return $element ? $element : null;
    }

    public static function toArray(IQueryable $queryable): array
    {
        return $queryable->getIterator()->toArray();
    }
}
