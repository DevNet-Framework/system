<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
