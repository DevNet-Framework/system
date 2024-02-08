<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
