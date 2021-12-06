<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use Closure;

class TaskCancelationToken
{
    private TaskCancelationSource $Source;
    private Closure $Action;
    private bool $IsCancellationRequested = false;

    public function __get(string $name)
    {
        if ($name == 'IsCancellationRequested') {
            return $this->Source->IsCancellationRequested;
        }

        return $this->$name;
    }

    public function __construct($source)
    {
        $this->Source = $source;
    }

    public function register(Closure $action)
    {
        $this->Action = $action;
    }
}
