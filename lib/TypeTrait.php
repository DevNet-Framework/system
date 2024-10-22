<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\TypeException;

trait TypeTrait
{
    private ?Type $__type = null;

    /**
     * Set the generic type arguments
     */
    protected function setGenericArguments(string ...$typeArguments): void
    {
        if (!$this->__type) {
            $this->__type = new Type(static::class, $typeArguments);
        }
    }

    /**
     * Check the argument types.
     * @throws TypeException with a code error that represents the index of the failed argument.
     */
    protected function checkArgumentTypes(array $args): void
    {
        $trace         = debug_backtrace();
        $methodName    = $trace[1]['function'];
        $methodInfo    = $this->getType()->getMethod($methodName);
        $genericArgs   = $this->getType()->getGenericArguments();
        $genericParams = $this->getType()->getGenericParameters();

        foreach ($methodInfo->getParameters() as $index => $param) {
            $typeAttribute = $param->getAttributes(Type::class)[0] ?? null;
            if ($typeAttribute) {
                $argument = $args[$index];
                $argumentType = Type::getType($argument);
                $parameterType = $typeAttribute->newInstance();
                if ($parameterType->isGenericParameter()) {
                    $key = array_search($parameterType, $genericParams);
                    $genericArgument = $genericArgs[$key] ?? null;
                    if ($genericArgument) {
                        // Replace the generic type parameter with the generic type argument.
                        $parameterType = $genericArgument;
                    }
                }

                if (!$argumentType->isAssignableTo($parameterType)) {
                    $index++;
                    throw new TypeException(static::class . "::{$methodName}(): Argument #{$index} must be of type {$parameterType}", $index, 2);
                }
            }
        }
    }

    /**
     * Get the type of the current object.
     */
    public function getType(): Type
    {
        if (!$this->__type) {
            $this->__type = new Type(static::class);
        }

        return $this->__type;
    }
}
