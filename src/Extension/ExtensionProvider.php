<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Extension;

class ExtensionProvider
{
    private static array $extensionMap = [];

    static function getClassNames() : array
    {
        $trace = debug_backtrace();
        $runtimeFile = $trace[2]["file"];
        $contents = file_get_contents($runtimeFile);

        preg_match_all("%(?i)use\s+([A-Za-z_\\\]+);%", $contents, $matches);

        return $matches[1];
    }

    static function addExtension(object $target, string $extenssionType)
    {
        $targetType = get_class($target);
        self::$extensionMap[$targetType][] = $extenssionType;
    }

    static function getExtensionMethod(object $target, string $methodName)
    {
        $targetClass = get_class($target);

        $extensionMethod = null;

        if (isset(self::$extensionMap[$targetClass]))
        {
            $extensionMethod = self::matchExtension($target, $methodName, self::$extensionMap[$targetClass]);
        }

        if ($extensionMethod)
        {
            return $extensionMethod;
        }

        return self::matchExtension($target, $methodName, self::getClassNames());
    }

    public static function matchExtension(object $target, string $methodName, array $classnames) : ?object
    {
        $targetClass = get_class($target);

        foreach ($classnames as $className)
        {
            if (class_exists($className))
            {
                self::$extensionMap[$targetClass][] = $className;
                if (method_exists($className, $methodName))
                {
                    $reflectionMethod = new \ReflectionMethod($className, $methodName);
                    $reflectionParams = $reflectionMethod->getParameters();
                    if (isset($reflectionParams[0]))
                    {
                        if ($reflectionParams[0]->hasType())
                        {
                            $fistParameterType = $reflectionParams[0]->getType()->getName();
                        }

                        if ($target instanceof $fistParameterType)
                        {
                            return $reflectionMethod;
                        }
                    }
                }
            }
        }

        return null;
    }
}
