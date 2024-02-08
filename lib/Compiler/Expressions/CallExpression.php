<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

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

    public function accept(ExpressionVisitor $visitor): void
    {
        $visitor->visitCall($this);
    }
}
