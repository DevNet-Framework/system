<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Linq\Expressions;

class CallExpression extends Expression
{
    public ?ParameterExpression $Object;
    public string $Method;
    public array $Arguments;

    public function __construct(?ParameterExpression $object, string $method, array $arguments = [])
    {
        $this->Object = $object;
        $this->Method = $method;
        $this->Arguments = $arguments;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitCall($this);
    }
}