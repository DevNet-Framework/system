<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

class FileStream extends Stream
{
    public function __construct(string $filename, string $mode, float $timeout = 0, bool $blocking = true)
    {
        $this->Timeout  = $timeout;
        $this->Blocking = $blocking;
        $this->Resource = fopen($filename, $mode);
        stream_set_blocking($this->Resource, $this->Blocking);
    }
}
