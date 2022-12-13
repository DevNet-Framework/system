<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

abstract class ExpressionVisitor
{
    public string $Out;

    public function visit(Expression $expression)
    {
        $expression->accept($this);
    }

    abstract public function visitLambda(Expression $expression): void;

    abstract public function visitCall(Expression $expression): void;

    abstract public function visitArray(Expression $expression): void;

    abstract public function visitGroup(Expression $expression): void;

    abstract public function visitBinary(Expression $expression): void;

    abstract public function visitProperty(Expression $expression): void;

    abstract public function visitParameter(Expression $expression): void;

    abstract public function visitConstant(Expression $expression): void;

    abstract public function visitUnary(Expression $expression): void;
}
