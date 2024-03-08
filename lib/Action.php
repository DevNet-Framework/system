<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use Closure;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;

class Action
{
    use PropertyTrait;

    public ReflectionFunctionAbstract $reflection;
    public $target;

    public function __construct(callable $target)
    {
        $this->target = $target;
        if (is_array($target)) {
            $this->reflection = new ReflectionMethod($target[0], $target[1]);
        } else if (is_object($target)) {
            if ($target instanceof Closure) {
                $this->reflection = new ReflectionFunction($target);
            } else {
                $this->reflection = new ReflectionMethod($target, '__invoke');
                $this->target = [$target, '__invoke'];
            }
        }
    }

    public function get_Reflection(): ReflectionFunctionAbstract
    {
        return $this->reflection;
    }

    public function invoke(...$args)
    {
        if ($this->reflection->isClosure()) {
            $function = $this->target;
            return $function(...$args);
        }

        $object = $this->target[0];
        $method = $this->target[1];
        return $object->$method(...$args);
    }

    public function __invoke(...$args)
    {
        $this->invoke(...$args);
    }
}
