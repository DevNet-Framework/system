<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Linq\Expressions;

class ExpressionType
{
    const Constant = 'Artister\System\Linq\Expressions\ConstantExpression';
    const Parameter = 'Artister\System\Linq\Expressions\ParameterExpression';
    const Property = 'Artister\System\Linq\Expressions\PropertyExpression';
    const Binary = 'Artister\System\Linq\Expressions\BinaryExpression';
}