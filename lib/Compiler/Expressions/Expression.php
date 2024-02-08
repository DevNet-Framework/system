<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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

    public static function lambda($predicate, array $parameters = [], ?string $returnType = null): LambdaExpression
    {
        return new LambdaExpression($predicate, $parameters, $returnType);
    }

    public static function call(?ParameterExpression $object, string $method, array $parameters = []): CallExpression
    {
        return new CallExpression($object, $method, $parameters);
    }

    public static function group(string $name, Expression $expression): GroupExpression
    {
        return new GroupExpression($name, $expression);
    }

    public static function parameter(string $name, ?string $type = null, $value = null): ParameterExpression
    {
        return new ParameterExpression($name, $type, $value);
    }

    public static function binary(string $name, Expression $left, Expression $right): BinaryExpression
    {
        return new BinaryExpression($name, $left, $right);
    }

    public static function property(ParameterExpression $parameter, string $property): PropertyExpression
    {
        return new PropertyExpression($parameter, $property);
    }

    public static function constant($value, ?string $type = null): ConstantExpression
    {
        return new ConstantExpression($value, $type);
    }

    public static function unary(string $name, Expression $operand): UnaryExpression
    {
        return new UnaryExpression($name, $operand);
    }
}
