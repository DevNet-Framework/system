<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;
use DevNet\System\Exceptions\MethodException;
use DevNet\System\Exceptions\TypeException;
use ArrayAccess;
use Closure;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;

abstract class Delegate implements ArrayAccess, IEnumerable
{
    protected ReflectionMethod $signature;
    protected array $functions = [];

    public function __construct(?callable $target = null)
    {
        $delegate = new ReflectionClass($this);
        if (!$delegate->hasMethod($delegate->getShortName())) {
            throw new MethodException("Missing delegate signature!");
        }

        $this->signature = $delegate->getMethod($delegate->getShortName());

        try {
            if ($target) {
                $this->offsetSet(null, $target);
            }
        } catch (TypeException $e) {
            throw new TypeException($e->getMessage(), 1, 0);
        }
    }

    public function offsetSet($key, $action): void
    {
        if (!is_callable($action)) {
            throw new TypeException("Illegal value type, the value must be of type callable", 0, 1);
        }

        if (is_array($action)) {
            $reflection = new ReflectionMethod($action[0], $action[1]);
            $action = $reflection->getClosure($action[0]);
        } else if (is_object($action) && !$action instanceof Closure) {
            $reflection = new ReflectionMethod($action, '__invoke');
            $action = $reflection->getClosure($action);
        }

        $function = new ReflectionFunction($action);

        if (is_null($key)) {
            $this->functions[] = $function;
        } else {
            if (!is_int($key)) {
                throw new TypeException("Illegal key type, the key must be of type integer or null", 1, 0);
            }
            $this->functions[$key] = $function;
        }


        foreach ($function->getParameters() as $index => $parameter) {
            if ($parameter->hasType()) {
                $parameterTypeName = $parameter->getType()->getName();
                $signatureParameter = $this->signature->getParameters()[$index] ?? null;

                $signatureTypeName = '';
                if ($signatureParameter && $signatureParameter->hasType()) {
                    $signatureTypeName = $signatureParameter->getType()->getName();
                }

                if ($parameterTypeName != $signatureTypeName && !is_subclass_of($parameterTypeName, $signatureTypeName)) {
                    throw new TypeException("The parameter type of the associated function not compatibale with the delegate " . $this::class, 0, 1);
                }
            }
        }

        if ($function->getReturnType() != $this->signature->getReturnType()) {
            throw new TypeException("The return type of the associated function not compatibale with the delegate " . $this::class, 0, 1);
        }
    }

    public function offsetGet($key): mixed
    {
        if (!is_int($key)) {
            throw new TypeException("Illegal key type, the key must be of type integer", 0, 1);
        }

        return $this->functions[$key] ?? null;
    }

    public function offsetUnset($key): void
    {
        if (!is_int($key)) {
            throw new TypeException("Illegal key type, the key must be of type integer", 0, 1);
        }

        unset($this->functions[$key]);
    }

    public function offsetExists($key): bool
    {
        if (!is_int($key)) {
            throw new TypeException("Illegal key type, the key must be of type integer", 0, 1);
        }

        return isset($this->functions[$key]);
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->functions);
    }


    public function invoke(...$args)
    {
        foreach ($this->functions as $function) {
            $result = $function->invoke(...$args);
        }

        if (isset($result)) {
            return $result;
        }
    }

    public function __invoke(...$args)
    {
        return $this->invoke(...$args);
    }

    public function __toString(): string
    {
        $parameters = [];
        foreach ($this->signature->getParameters() as $parameter) {
            $typeName = "mixed";
            if ($parameter->getType()) {
                $typeName = $parameter->getType()->getName();
            }

            $parameters[] = "{$typeName} \${$parameter->getName()}";
        }

        $parameters = implode(', ', $parameters);

        $returnTypeName = 'mixed';
        if ($this->signature->getReturnType()) {
            $returnTypeName = $this->signature->getReturnType()->getName();
        }

        $delegateName = get_class($this);
        return "{$delegateName} ({$parameters}) : {$returnTypeName}";
    }
}
