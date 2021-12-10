<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

interface IAwaiter
{
    /**
     * Check if this wait handle is Completed (succeeded, canceled or failed)
     */
    public function IsCompleted(): bool;
    
    /**
     * Wait this wait till is Completed then return the result.
     */
    public function getResult();
}
