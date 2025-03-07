<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

class CancellationSource
{
    private CancellationToken $token;
    private bool $isCancellationRequested = false;

    public CancellationToken $Token { get => $this->token; }
    public bool $IsCancellationRequested { get => $this->isCancellationRequested; }

    public function __construct()
    {
        $this->token = new CancellationToken($this);
    }

    public function cancel(): void
    {
        $this->isCancellationRequested = true;
    }
}
