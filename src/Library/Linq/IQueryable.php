<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Linq;

use Artister\System\Collections\IEnumerable;
use Closure;

interface IQueryable extends IEnumerable
{
    /**
     * This method must retun the value of following properites
     * @return string $ResultType
     * @return IQueryProvider $Provider
     * @return Expression $Expression
     */
    public function __get(string $name);
}