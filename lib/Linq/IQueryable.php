<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Linq;

use DevNet\System\Collections\IEnumerable;

interface IQueryable extends IEnumerable
{
    /**
     * This method must return the value of following properties
     * @return object $EntityType
     * @return IQueryProvider $Provider
     * @return Expression $Expression
     */
    public function __get(string $name);
}
