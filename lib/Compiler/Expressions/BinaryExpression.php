<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

class BinaryExpression extends Expression
{
    public string $Name;
    public Expression $Left;
    public Expression $Right;

    public function __construct(string $name, Expression $left, Expression $right)
    {
        $this->Name = $name;
        $this->Left = $left;
        $this->Right = $right;
    }

    public function accept(ExpressionVisitor $visitor): void
    {
        $visitor->visitBinary($this);
    }
}
