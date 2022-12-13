<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

class UnaryExpression extends Expression
{
    public string $Name;
    public Expression $Operand;

    public function __construct(string $name, Expression $operand)
    {
        $this->Name = $name;
        $this->Operand = $operand;
    }

    public function accept(ExpressionVisitor $visitor): void
    {
        $visitor->visitUnary($this);
    }
}
