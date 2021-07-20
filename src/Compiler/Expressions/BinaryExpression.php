<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

use DevNet\System\Compiler\ExpressionVisitor;

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

    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitBinary($this);
    }
}
