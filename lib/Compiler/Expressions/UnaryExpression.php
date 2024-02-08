<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
