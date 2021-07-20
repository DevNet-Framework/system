<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Linq;

use DevNet\System\Collections\IEnumerable;

interface IQueryable extends IEnumerable
{
    /**
     * This method must retun the value of following properites
     * @return object $EntityType
     * @return IQueryProvider $Provider
     * @return Expression $Expression
     */
    public function __get(string $name);
}
