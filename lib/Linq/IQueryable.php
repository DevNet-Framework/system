<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Linq;

use DevNet\System\Collections\IEnumerable;
use DevNet\System\Compiler\Expressions\Expression;

interface IQueryable extends IEnumerable
{
    public object $EntityType { get; }
    public IQueryProvider $Provider { get; }
    public Expression $Expression { get; }
}
