<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Event;

use DevNet\System\Action;
use DevNet\System\Collections\IEnumerable;
use DevNet\System\Collections\Enumerator;
use DevNet\System\Exceptions\MethodException;
use ReflectionMethod;

abstract class Delegate implements IEnumerable
{
    protected ReflectionMethod $MethodInfo;
    protected array $Parameters = [];
    protected array $Actions    = [];

    public function __construct(object $target = null, ?string $actionName = null)
    {
        if (!method_exists($this, 'delegate')) {
            throw new MethodException("Undefined signature, Delegate method not found");
        }

        $this->MethodInfo = new ReflectionMethod($this, 'delegate');

        foreach ($this->MethodInfo->getParameters() as $parameter) {
            $this->Parameters[] = $parameter;
        }

        if ($target) {
            $this->add($target, $actionName);
        }
    }

    public function add(object $target, ?string $actionName = null)
    {
        if (!$actionName) {
            $actionName = '__invoke';
        }

        $action = new Action([$target, $actionName]);

        if (!$this->matchSignature($action)) {
            throw new \Exception("incompatible signature, function must be compatible with : {$this->getSignature()}");
        }

        $this->Actions[] = $action;
    }

    public function matchSignature(Action $action): bool
    {
        if ($this->MethodInfo->getReturnType() && $this->MethodInfo->getReturnType() != $action->MethodInfo->getReturnType()) {
            return false;
        }

        $parameters = $action->MethodInfo->getParameters();
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
        if ($this->MethodInfo->getReturnType()) {
            $returnTypeName = $this->MethodInfo->getReturnType()->getName();
        }

        $delegateName = get_class($this);
        return "{$delegateName} ({$parameters}) : {$returnTypeName}";
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->Actions);
    }

    public function invokeArgs(array $arguments)
    {
        foreach ($this->Actions as $action) {
            $result = $action->invokeArgs($arguments);
        }

        if (isset($result)) {
            return $result;
        }
    }

    public function invoke(...$args)
    {
        return $this->invokeArgs($args);
    }

    public function __invoke(...$args)
    {
        return $this->invokeArgs($args);
    }
}
