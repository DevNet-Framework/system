<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

use DevNet\System\Compiler\ExpressionStringBuilder;

abstract class Expression
{
    abstract public function accept(ExpressionVisitor $expressionVisitor);

    public function __toString()
    {
        return ExpressionStringBuilder::expressionToString($this);
    }

    public static function lambda($predicate, array $parameters = [], ?string $returnType = null)
    {
        return new LambdaExpression($predicate, $parameters, $returnType);
    }

    public static function call(?ParameterExpression $object, string $method, array $parameters = [])
    {
        return new CallExpression($object, $method, $parameters);
    }

    public static function group(string $name, Expression $expression)
    {
        return new GroupExpression($name, $expression);
    }

    public static function parameter(string $name, ?string $type = null, $value = null)
    {
        return new ParameterExpression($name, $type, $value);
    }

    public static function binary(string $name, Expression $left, Expression $right)
    {
        return new BinaryExpression($name, $left, $right);
    }

    public static function property(ParameterExpression $parameter, string $property)
    {
        return new PropertyExpression($parameter, $property);
    }

    public static function constant($value, ?string $type = null)
    {
        return new ConstantExpression($value, $type);
    }

    public static function unary(string $name, Expression $operand)
    {
        return new UnaryExpression($name, $operand);
    }
}
