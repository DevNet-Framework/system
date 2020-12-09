<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Compiler\Expressions;

use Artister\System\Compiler\ExpressionVisitor;

class GroupExpression extends Expression
{
    public string $Name;
    public expression $Expression;

    public function __construct(string $name, expression $expression)
    {
        $this->Name = $name;
        $this->Expression = $expression;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitGroup($this);
    }
}