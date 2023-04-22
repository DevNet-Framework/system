<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Net;

use DevNet\System\IO\Stream;

class Socket extends Stream
{
    public function __construct(string $host, int $port, ?float $timeout = null)
    {
        $this->resource = fsockopen($host, $port, $errorCode, $errorMessage, $timeout);

        if (!$this->resource) {
            throw new NetworkException($errorMessage, $errorCode);
        }

        if ($timeout) {
            stream_set_timeout($this->resource, (int) $timeout, $timeout * 1000000 % 1000000);
        }
    }
}
