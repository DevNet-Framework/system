<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

use DevNet\System\Compiler\ExpressionVisitor;

class PropertyExpression extends Expression
{
    public ParameterExpression $parameter;
    public string $property;

    public function __construct(ParameterExpression $parameter, string $property)
    {
        $this->Parameter = $parameter;
        $this->Property = $property;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitProperty($this);
    }
}
