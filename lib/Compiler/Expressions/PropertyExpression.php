<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

class PropertyExpression extends Expression
{
    public ParameterExpression $Parameter;
    public string $Property;

    public function __construct(ParameterExpression $parameter, string $property)
    {
        $this->Parameter = $parameter;
        $this->Property = $property;
    }

    public function accept(ExpressionVisitor $visitor): void
    {
        $visitor->visitProperty($this);
    }
}
