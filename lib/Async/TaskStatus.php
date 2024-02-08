<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

Enum TaskStatus: int
{
    case Created   = 0;
    case Pending   = 1;
    case Running   = 2;
    case Succeeded = 3;
    case Canceled  = -1;
    case Failed    = -2;
}
