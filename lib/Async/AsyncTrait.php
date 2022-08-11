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
    public function __call(string $name, array $args)
    {
        $class = get_class($this);
        $asyncMethod = 'async_' . $name;
        if (method_exists($this, $asyncMethod)) {
            $action = new AsyncFunction([$this, $asyncMethod]);
            if (!$action->MethodInfo->isGenerator()) {
                throw new MethodException("The method {$class}::{$asyncMethod}() must use 'yield' keyword");
            }
            return $action->invokeArgs($args);
        }

        if (!method_exists($this, $name)) {
            throw new MethodException("Call to undefined method {$class}::{$name}()");
        }

        throw new MethodException("Call to non-public method {$class}::{$name}()");
    }
}