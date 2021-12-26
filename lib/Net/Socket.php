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
    public function __construct(string $host, int $port, float $timeout = 0, bool $blocking = true)
    {
        $this->Timeout  = $timeout;
        $this->Blocking = $blocking;

        if ($this->Timeout) {
            $this->Resource = fsockopen($host, $port, $errorCode, $errorMessage, $this->Timeout);
        } else {
            $this->Resource = fsockopen($host, $port, $errorCode, $errorMessage);
        }

        if (!$this->Resource) {
            throw new NetworkException($errorMessage, $errorCode);
        }

        stream_set_blocking($this->Resource, $this->Blocking);
    }
}
