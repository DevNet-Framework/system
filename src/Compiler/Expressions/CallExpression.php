<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

use DevNet\System\Compiler\ExpressionVisitor;

class CallExpression extends Expression
{
    public ?ParameterExpression $Object;
    public string $Method;
    public array $Arguments;

    public function __construct(?ParameterExpression $object, string $method, array $arguments = [])
    {
        $this->Object    = $object;
        $this->Method    = $method;
        $this->Arguments = $arguments;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitCall($this);
    }
}
