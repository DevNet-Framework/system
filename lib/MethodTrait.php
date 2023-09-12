<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Async\AsyncFunction;
use DevNet\System\Async\Task;
use DevNet\System\Exceptions\ArgumentException;
use DevNet\System\Exceptions\MethodException;
use ReflectionMethod;

trait MethodTrait
{
    private static ?Type $__type = null;

    public function __call(string $method, array $args)
    {
        if (!method_exists($this, $method)) {
            $asyncMethod = 'async_' . $method;
            if (method_exists($this, $asyncMethod)) {
                $action = new AsyncFunction([$this, $asyncMethod]);
                return $action->invoke($args);
            }

            $extension = Extender::getExtension($this, $method);
            if ($extension) {
                array_unshift($args, $this);
                return $extension::$method(...$args);
            }

            throw new MethodException("Call to undefined method "  . static::class . "::{$method}()", 0, 1);
        } else {
            $hasGenericParameter = false;
            $genericArgs = $this->getType()->getGenericArguments();
            if ($genericArgs) {
                $methodInfo = new ReflectionMethod($this, $method);
                foreach ($methodInfo->getParameters() as $index => $param) {
                    if ($param->hasType()) {
                        $paramTypeName = $param->getType()->getName();
                        $genericArgument = $genericArgs[$paramTypeName] ?? null;
                        if ($genericArgument) {
                            $hasGenericParameter = true;
                            $argument = $args[$index];
                            $typeArgument = Type::getType($argument);
                            if (!$typeArgument->isAssignableTo($genericArgument)) {
                                $index++;
                                throw new ArgumentException("Argument #{$index} must be of type {$genericArgument}", 0, 1);
                            }
                            $element = new $paramTypeName($typeArgument);
                            $element->Value = $args[$index];
                            $args[$index] = $element;
                        }
                    }
                }

                if ($hasGenericParameter) {
                    $methodInfo->setAccessible(true);
                    return $methodInfo->invokeArgs($this, $args);
                }

                $modifier = 'private';
                if ($methodInfo->isProtected()) {
                    $modifier = 'protected';
                }

                throw new MethodException("Call to {$modifier} method " . static::class . "::{$method}()", 0, 1);
            }
        }
    }

    public function __invoke(...$args): Task
    {
        if (method_exists($this, "async_invoke")) {
            $action = new AsyncFunction([$this, "async_invoke"]);
            return $action->invoke($args);
        }

        throw new MethodException("Can not invoke object of type " . $this::class, 0, 1);
    }

    protected function setGenericArguments(string ...$typeArguments): void
    {
        if (!static::$__type) {
            static::$__type = new Type(static::class, $typeArguments);
        }
    }

    /**
     * Get the type of the current object.
     */
    public function getType(): Type
    {
        if (!static::$__type) {
            static::$__type = new Type(static::class);
        }

        return static::$__type;
    }
}
