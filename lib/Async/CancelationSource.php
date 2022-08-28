<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\ObjectTrait;

class CancelationSource
{
    use ObjectTrait;

    private CancelationToken $token;
    private bool $isCancellationRequested = false;

    public function __construct()
    {
        $this->token = new CancelationToken($this);
    }

    public function get_Token(): CancelationToken
    {
        return $this->token;
    }

    public function get_IsCancellationRequested(): bool
    {
        return $this->isCancellationRequested;
    }

    public function cancel(): void
    {
        $this->isCancellationRequested = true;
    }
}
