<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Extension;

use DevNet\System\Exceptions\MethodException;

trait ExtensionTrait
{
    public function __call($method, $args)
    {
        $extensionMethod = ExtensionProvider::getExtensionMethod($this, $method);
        if ($extensionMethod) {
            array_unshift($args, $this);
            return $extensionMethod->invokeArgs(null, $args);
        }

        $class = get_class($this);
        if (!method_exists($this, $method)) {
            throw new MethodException("Call to undefined method {$class}::{$method}()");
        }

        throw new MethodException("Call to non-public method {$class}::{$method}()");
    }

    public function extend(string $extenssion)
    {
        ExtensionProvider::addExtension($this, $extenssion);
    }
}
