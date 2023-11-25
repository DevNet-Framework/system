<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

class ExpressionType
{
    const Constant  = ConstantExpression::class;
    const Parameter = ParameterExpression::class;
    const Property  = PropertyExpression::class;
    const Binary    = BinaryExpression::class;
}
