<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Linq\Expressions;

class ExpressionStringBuilder extends ExpressionVisitor
{
    public string $Out = '';

    public static function expressionToString(Expression $expression) : string
    {
        $visitor = new ExpressionStringBuilder();
        $visitor->visit($expression);
        return $visitor->__toString();
    }

    public function visitLambda(Expression $expression)
    {
        $parameters = [];
        foreach ($expression->Parameters as $parameter) {
            if ($parameter->Type) {
                $parameters[] = $parameter->Type .' $'. $parameter->Name;
            } else {
                $parameters[] = '$'.$parameter->Name;
            }
        }

        $parameters = implode(', ', $parameters);
        $this->Out .= "fn({$parameters}) => ";
        $this->visit($expression->Body);
        return $expression;
    }

    public function visitCall(Expression $expression)
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
        $this->Out .=")";
        return $expression;
    }

    public function visitGroup(Expression $expression)
    {
        $this->Out .= "(";
        $this->visit($expression->Expression);
        $this->Out .= ")";
        return $expression;
    }

    public function visitBinary(Expression $expression)
    {
        $this->visit($expression->Left);
        $this->Out .= ' '.$expression->Name.' ';
        $this->visit($expression->Right);
        return $expression;
    }

    public function visitProperty(Expression $expression)
    {
        $this->visit($expression->Parameter);
        $this->Out .= '->'.$expression->Property;
        return $expression;
    }

    public function visitParameter(Expression $expression)
    {
        $this->Out .= $expression->Name;
        return $expression;
    }

    public function visitConstant(Expression $expression)
    {
        $this->Out .= $expression->Value;
        return $expression;
    }

    public function visitUnary(Expression $expression)
    {
        if ($expression->operand instanceof ConstantExpression) {
            $operand = $expression->operand->Value;
        } else if ($expression->operand instanceof ParameterExpression) {
            $operand = $expression->operand->Name;
        }
        $this->Out .= "{$expression->Name}{$operand}";
        return $expression;
    }

    public function __toString()
    {
        return $this->Out;
    }
}