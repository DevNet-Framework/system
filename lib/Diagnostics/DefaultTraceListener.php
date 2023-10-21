<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DevNet\System\IO\FileAccess;
use DevNet\System\IO\FileMode;
use DevNet\System\IO\FileStream;

class DefaultTraceListener extends WriterTraceListener
{
    public function __construct()
    {
        $this->Writer = new FileStream('php://stdout', FileMode::Open, FileAccess::Write);
    }
}
