<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Tasks;

use Closure;
use DevNet\System\ObjectTrait;

class CancelationToken
{
    use ObjectTrait;

    private CancelationSource $source;
    private Closure $action;
    private bool $isCancellationRequested = false;

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
        return $this->ssCancellationRequested;
    }

    public function register(Closure $action)
    {
        $this->action = $action;
    }
}
