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

    abstract public function visitLambda(Expression $expression);

    abstract public function visitCall(Expression $expression);

    abstract public function visitArray(Expression $expression);

    abstract public function visitGroup(Expression $expression);

    abstract public function visitBinary(Expression $expression);

    abstract public function visitProperty(Expression $expression);

    abstract public function visitParameter(Expression $expression);

    abstract public function visitConstant(Expression $expression);

    abstract public function visitUnary(Expression $expression);
}
