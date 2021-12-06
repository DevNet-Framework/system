<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

class TaskCancelationSource
{
    private TaskCancelationToken $Token;
    private bool $IsCancellationRequested = false;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct()
    {
        $this->Token = new TaskCancelationToken($this);
    }

    public function cancel(): void
    {
        $this->IsCancellationRequested = true;
    }
}
