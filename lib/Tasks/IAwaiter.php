<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Tasks;

use Closure;

interface IAwaiter
{
    /**
     * Set the continuation handler to the property OnCompleted event.
     * If the OnCompleted event is not null, it must be triggered only once after the wait handle is completed when the user calls the getResult method.
     */
    public function onCompleted(Closure $continuation): void;


    /**
     * Check if this wait handle is Completed (succeeded, canceled or failed)
     */
    public function IsCompleted(): bool;
    
    /**
     * Wait this wait handle till is Completed then return the result.
     */
    public function getResult();
}
