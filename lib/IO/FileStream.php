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
    public function __construct(string $filename, string $mode, float $timeout = 0)
    {
        $this->resource = fopen($filename, $mode);

        if ($timeout) {
            stream_set_timeout($this->resource, (int) $timeout, $timeout * 1000000 % 1000000);
        }
    }
}
