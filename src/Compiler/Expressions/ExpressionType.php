<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

class ExpressionType
{
    const Constant = 'DevNet\System\Linq\Expressions\ConstantExpression';
    const Parameter = 'DevNet\System\Linq\Expressions\ParameterExpression';
    const Property = 'DevNet\System\Linq\Expressions\PropertyExpression';
    const Binary = 'DevNet\System\Linq\Expressions\BinaryExpression';
}
