<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

enum SeekOrigin: int
{
    case Begin   = 0;
    case Current = 1;
    case End     = 2;
}
