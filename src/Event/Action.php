<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Event;

use DevNet\System\Exceptions\MethodException;
use ReflectionFunction;
use ReflectionMethod;
use Closure;
use Reflector;

class Action
{
    private object $Target;
    private string $ActionName;
    private Reflector $ActionInfo;

    public function __construct(object $target, string $actionName = '__invoke')
    {
        $this->Target     = $target;
        $this->ActionName = $actionName;

        if ($target instanceof Closure) {
            $this->ActionInfo = new ReflectionFunction($target);
        } else {
            if (!method_exists($target, $actionName)) {
                throw MethodException::undefinedMethod(get_class($target), $actionName);
            }

            $this->ActionInfo = new ReflectionMethod($target, $actionName);
        }
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __invoke(...$args)
    {
        $this->invokeArgs($args);
    }

    public function invokeArgs(array $args = [])
    {
        if ($this->Target instanceof Closure) {
            return $this->ActionInfo->invokeArgs($args);
        } else {
            return $this->ActionInfo->invokeArgs($this->Target, $args);
        }
    }
}
