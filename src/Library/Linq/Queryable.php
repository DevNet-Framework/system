<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Linq;

use Artister\System\Linq\IQueryable;
use Artister\System\Compiler\Expressions\Expression;
use Closure;

abstract class Queryable
{
    public static function where(IQueryable $queryable, Closure $predicate) : IQueryable
    {
        $lambda = Expression::Lambda($predicate);
        $expression = Expression::call(null, 'Where', [$queryable->Expression, $lambda]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function orderBy(IQueryable $queryable, Closure $predicate) : IQueryable
    {
        $lambda = Expression::Lambda($predicate);
        $expression = Expression::call(null, 'OrderBy', [$queryable->Expression, $lambda]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function orderByDescending(IQueryable $queryable, Closure $predicate) : IQueryable
    {
        $lambda = Expression::Lambda($predicate);
        $expression = Expression::call(null, 'OrderByDescending', [$queryable->Expression, $lambda]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function thenBy(IQueryable $queryable, Closure $predicate) : IQueryable
    {
        $lambda = Expression::Lambda($predicate);
        $expression = Expression::call(null, 'ThenBy', [$queryable->Expression, $lambda]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function thenByDescending(IQueryable $queryable, Closure $predicate) : IQueryable
    {
        $lambda = Expression::Lambda($predicate);
        $expression = Expression::call(null, 'ThenByDescending', [$queryable->Expression, $lambda]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function skip(IQueryable $queryable, int $offset) : IQueryable
    {
        $constant = Expression::constant($offset);
        $expression = Expression::call(null, 'skip', [$queryable->Expression, $constant]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function take(IQueryable $queryable, int $limit) : IQueryable
    {
        $constant = Expression::constant($limit);
        $expression = Expression::call(null, 'take', [$queryable->Expression, $constant]);
        return $queryable->Provider->createQuery($queryable->EntityType, $expression);
    }

    public static function first(IQueryable $queryable)
    {
        $array      = $queryable->getIterator()->toArray();
        $element    = reset($array);

        return $element ? $element : null;
    }

    public static function last(IQueryable $queryable)
    {
        $array      = $queryable->getIterator()->toArray();
        $element    = end($array);
        
        return $element ?? null;
    }
}