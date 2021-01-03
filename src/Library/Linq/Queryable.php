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
}