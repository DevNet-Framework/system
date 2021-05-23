<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Linq;

use DevNet\System\Compiler\Expressions\Expression;
use DevNet\System\Linq\IQueryable;

interface IQueryProvider
{
    public function createQuery(object $entityType, Expression $expression) : IQueryable;

    public function execute(object $entityType, Expression $expression);
}
