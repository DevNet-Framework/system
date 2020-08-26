<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Linq\Expressions;

class ParameterExpression extends Expression
{
    public string $Name;
    public ?string $Type;
    public $value;

    public function __construct(string $name, ?string $type = null, $value = null)
    {
        $this->Name = $name;
        $this->Type = $type;
        $this->Value = $value;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitParameter($this);
    }
}