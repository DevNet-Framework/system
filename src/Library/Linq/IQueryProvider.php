<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Linq;

use Artister\System\Linq\Expressions\Expression;
use Artister\System\Linq\IQueryable;

interface IQueryProvider
{
    public function createQuery(string $resultType, Expression $expression);

    public function execute(string $resultType, Expression $expression);
}