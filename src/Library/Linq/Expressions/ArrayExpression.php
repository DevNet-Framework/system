<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Linq\Expressions;

class ArrayExpression extends Expression
{
    public ParameterExpression $Parameter;
    public array $Arguments;

    public function __construct(ParameterExpression $parameter, array $arguments)
    {
        $this->Parameter = $parameter;
        $this->Arguments = $arguments;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitArray($this);
    }
}