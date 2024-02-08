<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

class ArrayExpression extends Expression
{
    public ParameterExpression $Parameter;
    public array $Arguments;

    public function __construct(ParameterExpression $parameter, array $arguments)
    {
        $this->Parameter = $parameter;
        $this->Arguments = $arguments;
    }

    public function accept(ExpressionVisitor $visitor): void
    {
        $visitor->visitArray($this);
    }
}
