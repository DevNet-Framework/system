<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
