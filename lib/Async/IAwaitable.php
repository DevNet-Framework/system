<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

interface IAwaitable
{
    /**
     * return wait handle of type IAwaiter.
     */
    public function getAwaiter(): IAwaiter;
}
