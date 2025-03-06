<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\PropertyTrait;
use Closure;

class CancellationToken
{
    use PropertyTrait;

    private CancellationSource $source;
    private bool $isCancellationRequested = false;
    private ?Closure $action = null;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function get_Source(): CancellationSource
    {
        return $this->source;
    }

    public function get_Action(): Closure
    {
        return $this->action;
    }

    public function get_IsCancellationRequested(): bool
    {
        return $this->isCancellationRequested;
    }

    public function register(Closure $action): void
    {
        $this->action = $action;
    }
}
