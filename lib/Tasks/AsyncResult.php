<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Tasks;

class AsyncResult implements IAwaitable
{
    private IAwaiter $awaiter;

    public function __construct($result = null, ?CancelationToken $token = null)
    {
        $this->awaiter = new AsyncAwaiter($result, $token);
    }

    public function getAwaiter(): IAwaiter
    {
        return $this->awaiter;
    }
}
