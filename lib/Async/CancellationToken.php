<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use Closure;

class CancellationToken
{
    private CancellationSource $source;
    private bool $isCancellationRequested = false;
    private ?Closure $action = null;

    public CancellationSource $Source { get => $this->source; }
    public Closure $Action { get => $this->action; }
    public bool $IsCancellationRequested { get => $this->isCancellationRequested; }

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function register(Closure $action): void
    {
        $this->action = $action;
    }
}
