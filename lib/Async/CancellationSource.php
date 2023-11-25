<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\PropertyTrait;

class CancellationSource
{
    use PropertyTrait;

    private CancellationToken $token;
    private bool $isCancellationRequested = false;

    public function __construct()
    {
        $this->token = new CancellationToken($this);
    }

    public function get_Token(): CancellationToken
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
