<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

class AsyncResult implements IAwaitable
{
    private IAwaiter $Awaiter;

    public function __construct($result = null, ?CancelationToken $token = null)
    {
        $this->Awaiter = new AsyncAwaiter($result, $token);
    }

    public function getAwaiter(): IAwaiter
    {
        return $this->Awaiter;
    }
}
