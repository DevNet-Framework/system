<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Async\AsyncTrait;
use DevNet\System\Exceptions\PropertyException;
use DevNet\System\Extension\ExtensionTrait;
use DevNet\System\Reflection\ReflectionTrait;

trait ObjectTrait
{
    use AsyncTrait, ExtensionTrait, ReflectionTrait {
        AsyncTrait::__call as private callAsync;
        ExtensionTrait::__call as private callExtension;
    }

    public function __call(string $name, array $args)
    {
        try {
            if (method_exists($this, 'async_' . $name)) {
                return $this->callAsync($name, $args);
            } else {
                return $this->callExtension($name, $args);
            }
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function __get(string $name)
    {
        $method = 'get_' . $name;
        if (method_exists($this, $method)) {
            $property = substr(strrchr($method, '_'), 1);
            if ($name == $property) {
                return $this->$method();
            }
        }

        $class = get_class($this);
        if (!property_exists($this, $name)) {
            throw new PropertyException("Access to undefined property {$class}::{$name}");
        }

        throw new PropertyException("Access to non-public property {$class}::{$name}");
    }

    public function __set(string $name, $value): void
    {
        $method = 'set_' . $name;
        if (method_exists($this, $method)) {
            $property = substr(strrchr($method, '_'), 1);
            if ($name == $property) {
                $this->$method($value);
                return;
            }
        }

        $class = get_class($this);
        if (!property_exists($this, $name)) {
            throw new PropertyException("Access to undefined property {$class}::{$name}");
        }

        throw new PropertyException("Access to non-public property {$class}::{$name}");
    }
}
