<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Extension;

use BadMethodCallException;

trait ExtensionTrait
{
    public function __call($methodName, $args)
    {
        $extensionMethod = ExtensionProvider::getExtensionMethod($this, $methodName);

        if ($extensionMethod) {
            array_unshift($args, $this);
            return $extensionMethod->invokeArgs(null, $args);
        }

        $class = get_class($this);
        throw new BadMethodCallException("Call to undefined method {$class}::{$methodName}()");
    }

    public function extend(string $extenssionType)
    {
        ExtensionProvider::addExtension($this, $extenssionType);
    }
}
