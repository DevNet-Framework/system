<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

enum FileAccess: int
{
    case Read = 1;
    case Write = 2;
    case ReadWrite = 3;
}
