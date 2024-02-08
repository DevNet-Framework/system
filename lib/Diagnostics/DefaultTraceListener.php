<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
