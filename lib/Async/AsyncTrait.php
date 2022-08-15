<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DevNet\System\Exceptions\MethodException;

trait AsyncTrait
{
    public function __call(string $method, array $args)
    {
        $asyncMethod = 'async_' . $method;
        if (method_exists($this, $asyncMethod)) {
            $action = new AsyncFunction([$this, $asyncMethod]);
            return $action->invokeArgs($args);
        }

        $class = get_class($this);
        if (!method_exists($this, $method)) {
            throw new MethodException("Call to undefined method {$class}::{$method}()");
        }

        throw new MethodException("Call to non-public method {$class}::{$method}()");
    }
}