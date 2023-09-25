<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\TypeException;

trait TypeTrait
{
    private static ?Type $__type = null;

    /**
     * Set the generic type arguments
     */
    protected function setGenericArguments(string ...$typeArguments): void
    {
        if (!static::$__type) {
            static::$__type = new Type(static::class, $typeArguments);
        }
    }

    /**
     * Check the argument types.
     * @throws TypeException with a code error that represents the index of the failed argument.
     */
    protected function checkArgumentTypes(array $args): void
    {
        $trace = debug_backtrace();
        $methodName = $trace[1]['function'];
        $genericArgs = $this->getType()->getGenericArguments();
        if ($genericArgs) {
            $methodInfo = $this->getType()->getMethod($methodName);
            foreach ($methodInfo->getParameters() as $index => $param) {
                $typeAttribute = $param->getAttributes(Type::class)[0] ?? null;
                if ($typeAttribute) {
                    $argument = $args[$index];
                    $argumentType = Type::getType($argument);
                    $parametertype = $typeAttribute->newInstance();
                    if ($parametertype->isGenericParameter()) {
                        $genericArgument = $genericArgs[$parametertype->Name] ?? null;
                        if ($genericArgument) {
                            // Replace the generic type parameter with the generic type argument.
                            $parametertype = $genericArgument;
                        }
                    }

                    if (!$argumentType->isAssignableTo($parametertype)) {
                        $index++;
                        throw new TypeException(static::class . "::{$methodName}(): Argument #{$index} must be of type {$parametertype}", $index, 2);
                    }
                }
            }
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
