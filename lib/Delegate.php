<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;
use DevNet\System\Exceptions\MethodException;
use DevNet\System\Exceptions\TypeException;
use ArrayAccess;
use ReflectionClass;
use ReflectionMethod;

abstract class Delegate implements ArrayAccess, IEnumerable
{
    protected ReflectionMethod $signature;
    protected array $actions = [];

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

    public function offsetSet($key, $function): void
    {
        if (!is_callable($function)) {
            throw new TypeException("Illegal value type, the value must be of type callable", 0, 1);
        }

        $action = new Action($function);

        if (is_null($key)) {
            $this->actions[] = $action;
        } else {
            if (!is_int($key)) {
                throw new TypeException("Illegal key type, the key must be of type integer or null", 1, 0);
            }
            $this->actions[$key] = $action;
        }

        foreach ($action->Reflection->getParameters() as $index => $parameter) {
            if ($parameter->hasType()) {
                $parameterTypeName = $parameter->getType()->getName();
                $signatureParameter = $this->signature->getParameters()[$index] ?? null;

                $signatureTypeName = '';
                if ($signatureParameter && $signatureParameter->hasType()) {
                    $signatureTypeName = $signatureParameter->getType()->getName();
                }

                if ($parameterTypeName != $signatureTypeName && !is_subclass_of($parameterTypeName, $signatureTypeName)) {
                    throw new TypeException("The parameter type of the associated function not compatible with the delegate " . $this::class, 0, 1);
                }
            }
        }

        if ($this->signature->hasReturnType() && $this->signature->getReturnType() != $action->Reflection->getReturnType()) {
            throw new TypeException("The return type of the associated function not compatible with the delegate " . $this::class, 0, 1);
        }
    }

    public function offsetGet($key): mixed
    {
        if (!is_int($key)) {
            throw new TypeException("Illegal key type, the key must be of type integer", 0, 1);
        }

        return $this->actions[$key] ?? null;
    }

    public function offsetUnset($key): void
    {
        if (!is_int($key)) {
            throw new TypeException("Illegal key type, the key must be of type integer", 0, 1);
        }

        unset($this->actions[$key]);
    }

    public function offsetExists($key): bool
    {
        if (!is_int($key)) {
            throw new TypeException("Illegal key type, the key must be of type integer", 0, 1);
        }

        return isset($this->actions[$key]);
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->actions);
    }

    public function invoke(...$args)
    {
        foreach ($this->actions as $action) {
            $result = $action->invoke(...$args);
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
