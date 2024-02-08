<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Dependency;

use DevNet\System\Exceptions\ClassException;
use ReflectionClass;

class Activator
{
    public static function CreateInstance(string $type, IServiceProvider $provider = null): object
    {
        if (!class_exists($type)) {
            throw new ClassException("Cound not find class {$type}", 0, 1);
        }

        $classInfo  = new ReflectionClass($type);
        $methodInfo = $classInfo->getConstructor();

        if (!$methodInfo || !$provider) {
            return $classInfo->newInstance();
        }

        $parameters = $methodInfo->getParameters();
        $arguments  = [];

        foreach ($parameters as $parameter) {
            $parameterType = '';
            if ($parameter->getType()) {
                $parameterType = $parameter->getType()->getName();
            }

            if (!$provider->contains($parameterType)) {
                break;
            }

            $arguments[] = $provider->getService($parameterType);
        }

        return $classInfo->newInstanceArgs($arguments);
    }
}
