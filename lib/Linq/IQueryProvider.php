<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Linq;

use DevNet\System\Compiler\Expressions\Expression;
use DevNet\System\Linq\IQueryable;

interface IQueryProvider
{
    public function createQuery(object $entityType, Expression $expression): IQueryable;

    public function execute(object $entityType, Expression $expression): array;
}
