<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler;

use DevNet\System\Compiler\Expressions\Expression;
use DevNet\System\Compiler\Expressions\ConstantExpression;
use DevNet\System\Compiler\Expressions\ExpressionVisitor;
use DevNet\System\Compiler\Expressions\ParameterExpression;

class ExpressionStringBuilder extends ExpressionVisitor
{
    public string $Out = '';

    public static function expressionToString(Expression $expression): string
    {
        $visitor = new ExpressionStringBuilder();
        $visitor->visit($expression);
        return $visitor->__toString();
    }

    public function visitLambda(Expression $expression): void
    {
        $parameters = [];
        foreach ($expression->Parameters as $parameter) {
            if ($parameter->Type) {
                $parameters[] = $parameter->Type . ' $' . $parameter->Name;
            } else {
                $parameters[] = '$' . $parameter->Name;
            }
        }

        $parameters = implode(', ', $parameters);
        $this->Out .= "fn({$parameters}) => ";
        $this->visit($expression->Body);
    }

    public function visitCall(Expression $expression): void
    {
        $arguments = $expression->Arguments;
        $lastExpression = array_shift($arguments);
        if ($lastExpression) {
            $this->visit($lastExpression);
        }

        if ($expression->Object) {
            $this->Out .= "{$expression->Object->Name}->{$expression->Method}(";
        } else {
            $this->Out .= "{$expression->Method}(";
        }

        foreach ($arguments as $argument) {
            $this->visit($argument);
        }
        $this->Out .= ")";
    }

    public function visitArray(Expression $expression): void
    {
        # code...
    }

    public function visitGroup(Expression $expression): void
    {
        $this->Out .= "(";
        $this->visit($expression->Expression);
        $this->Out .= ")";
    }

    public function visitBinary(Expression $expression): void
    {
        $this->visit($expression->Left);
        $this->Out .= ' ' . $expression->Name . ' ';
        $this->visit($expression->Right);
    }

    public function visitProperty(Expression $expression): void
    {
        $this->visit($expression->Parameter);
        $this->Out .= '->' . $expression->Property;
    }

    public function visitParameter(Expression $expression): void
    {
        $this->Out .= $expression->Name;
    }

    public function visitConstant(Expression $expression): void
    {
        $this->Out .= $expression->Value;
    }

    public function visitUnary(Expression $expression): void
    {
        if ($expression->operand instanceof ConstantExpression) {
            $operand = $expression->operand->Value;
        } else if ($expression->operand instanceof ParameterExpression) {
            $operand = $expression->operand->Name;
        }
        $this->Out .= "{$expression->Name}{$operand}";
    }

    public function __toString(): string
    {
        return $this->Out;
    }
}
