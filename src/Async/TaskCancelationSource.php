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
    private bool $IsCanceled = false;

    public function __construct()
    {
        $this->Token = new TaskCancelationToken($this);
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function cancel(): void
    {
        $this->IsCanceled = true;
    }
}
