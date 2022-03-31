<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\Exceptions\PropertyException;

class CancelationSource
{
    private CancelationToken $token;
    private bool $isCancellationRequested = false;

    public function __get(string $name)
    {
        if ($name == 'Token') {
            return $this->token;
        }

        if ($name == 'IsCancellationRequested') {
            return $this->isCancellationRequested;
        }
        
        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
    }

    public function __construct()
    {
        $this->token = new CancelationToken($this);
    }

    public function cancel(): void
    {
        $this->isCancellationRequested = true;
    }
}
