<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

class ParameterExpression extends Expression
{
    public string $Name;
    public ?string $Type;
    public $Value;

    public function __construct(string $name, ?string $type = null, $value = null)
    {
        $this->Name = $name;
        $this->Type = $type;
        $this->Value = $value;
    }

    public function accept(ExpressionVisitor $visitor): void
    {
        $visitor->visitParameter($this);
    }
}
