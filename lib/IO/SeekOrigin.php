<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

enum SeekOrigin: int
{
    case Begin   = SEEK_SET;
    case Current = SEEK_CUR;
    case End     = SEEK_END;
}
