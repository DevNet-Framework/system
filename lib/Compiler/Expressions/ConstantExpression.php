<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

class ConstantExpression extends Expression
{
    public $Value;
    public ?string $Type;

    public function __construct($value, ?string $type = null)
    {
        $this->Value = $value;
        $this->Type = $type;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitConstant($this);
    }
}
