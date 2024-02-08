<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

class GroupExpression extends Expression
{
    public string $Name;
    public expression $Expression;

    public function __construct(string $name, expression $expression)
    {
        $this->Name = $name;
        $this->Expression = $expression;
    }

    public function accept(ExpressionVisitor $visitor): void
    {
        $visitor->visitGroup($this);
    }
}
