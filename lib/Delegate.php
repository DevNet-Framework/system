<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Action;
use DevNet\System\Collections\IEnumerable;
use DevNet\System\Collections\Enumerator;
use DevNet\System\Exceptions\MethodException;
use DevNet\System\Exceptions\TypeException;
use ReflectionMethod;

abstract class Delegate implements IEnumerable
{
    protected ReflectionMethod $Method;
    protected array $Parameters = [];
    protected array $Actions    = [];

    public function __construct(?callable $action = null)
    {
        if (!method_exists($this, 'delegate')) {
            throw new MethodException("Undefined signature, Delegate method not found", 0, 1);
        }

        $this->Method = new ReflectionMethod($this, 'delegate');

        foreach ($this->Method->getParameters() as $parameter) {
            $this->Parameters[] = $parameter;
        }

        if ($action) {
            try {
                $this->add($action);
            } catch (\Throwable $error) {
                if ($error instanceof TypeException) {
                    throw new TypeException($error->getMessage(), $error->getCode(), 1);
                }

                throw $error;
            }
        }
    }

    public function add(callable $action): void
    {
        $action = new Action($action);

        if (!$this->matchSignature($action)) {
            $delegate = $this::class;
            throw new TypeException("The delegated function must be compatible with the signature of the delegate {$delegate}", 0, 1);
        }

        $this->Actions[] = $action;
    }

    public function matchSignature(Action $action): bool
    {
        if ($this->Method->getReturnType() && $this->Method->getReturnType() != $action->Function->getReturnType()) {
            return false;
        }

        $parameters = $action->Function->getParameters();
        foreach ($parameters as $index => $parameter) {
            if ($parameter->hasType()) {
                $typeName = $parameter->getType()->getName();
                $typeSignature = $this->Parameters[$index]->getType()->getName();
                if ($typeName != $typeSignature && !is_subclass_of($typeName, $typeSignature)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function getSignature(): string
    {
        $parameters = [];
        foreach ($this->Parameters as $parameter) {
            $typeName = "mixed";
            if ($parameter->getType()) {
                $typeName = $parameter->getType()->getName();
            }

            $parameters[] = "{$typeName} \${$parameter->getName()}";
        }

        $parameters = implode(', ', $parameters);

        $returnTypeName = 'mixed';
        if ($this->Method->getReturnType()) {
            $returnTypeName = $this->Method->getReturnType()->getName();
        }

        $delegateName = get_class($this);
        return "{$delegateName} ({$parameters}) : {$returnTypeName}";
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->Actions);
    }

    public function invoke(array $args = [])
    {
        foreach ($this->Actions as $action) {
            $result = $action->invoke($args);
        }

        if (isset($result)) {
            return $result;
        }
    }

    public function __invoke(...$args)
    {
        return $this->invoke($args);
    }
}
