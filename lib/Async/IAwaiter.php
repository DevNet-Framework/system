<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

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
