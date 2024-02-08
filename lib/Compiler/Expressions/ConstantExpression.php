<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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

    public function accept(ExpressionVisitor $visitor): void
    {
        $visitor->visitConstant($this);
    }
}
