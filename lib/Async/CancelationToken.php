<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\ObjectTrait;
use Closure;

class CancelationToken
{
    use ObjectTrait;

    private CancelationSource $source;
    private bool $isCancellationRequested = false;
    private ?Closure $action = null;

    public function __construct($source)
    {
        $this->Source = $source;
    }

    public function get_Source(): CancelationSource
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
