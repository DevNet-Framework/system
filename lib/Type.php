<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\ArrayException;
use DevNet\System\Exceptions\TypeException;
use Attribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

#[Attribute]
class Type
{
    public readonly string $Name;
    private array $parameters = [];
    private array $arguments  = [];

    private static array $properties = [];
    private static array $methods    = [];

    public function __construct(string $name, array $arguments = [])
    {
        // normalizing the built-in type names
        switch (strtolower($name)) {
            case 'null':
                $this->Name = 'null';
                break;
            case 'boolean':
            case 'bool':
                $this->Name = 'boolean';
                break;
            case 'integer':
            case 'int':
                $this->Name = 'integer';
                break;
            case 'float':
            case 'double':
                $this->Name = 'float';
                break;
            case 'string':
                $this->Name = 'string';
                break;
            case 'array':
                $this->Name = 'array';
                break;
            case 'object':
                $this->Name = 'object';
                break;
            case 'callable':
                $this->Name = 'callable';
            case 'mixed':
                $this->Name = 'mixed';
                break;
            default:
                // The remaining case is considered a class or type parameter.
                $this->Name = $name;
                break;
        }

        // Set the type parameters and arguments
        if ($this->isClass()) {
            $class = new ReflectionClass($this->Name);
            foreach ($class->getAttributes(Template::class) as $attribute) {
                $generic = $attribute->newInstance();
                foreach ($generic->getTypes() as $type) {
                    if (!$type->isGenericParameter()) {
                        throw new TypeException("The generic type parameter should not be an existing type.", 0, 1);
                    }

                    if (isset($this->parameters[$type->Name])) {
                        throw new TypeException("The generic type should not have a repeated generic parameter.", 0, 1);
                    }

                    $this->parameters[$type->Name] = $type;
                }

                // No need to look for other attributes.
                break;
            }

            // Convert associative array to indexed array
            $this->parameters = array_values($this->parameters);
        }

        if ($this->parameters && count($this->parameters) != count($arguments)) {
            throw new ArrayException("The number of generic type arguments must be equal to the number of generic type parameters.", 0, 1);
        }

        foreach ($arguments as $argument) {
            if (!is_string($argument)) {
                throw new ArrayException("Type arguments must be of type array<int, string>", 0, 1);
            }
            $this->arguments[] = new Type($argument);
        }
    }

    public function makeGenericType(array $typeArguments): Type
    {
        return new Type($this->Name, $typeArguments);
    }

    public function getGenericParameters(): array
    {
        return $this->parameters;
    }

    public function getGenericArguments(): array
    {
        return $this->arguments;
    }

    public function getInterfaces(): array
    {
        $interfaces = class_implements($this->Name);
        if (!$interfaces) {
            $interfaces = [];
        }
        return $interfaces;
    }

    public function getProperty(string $property): ?ReflectionProperty
    {
        if (isset(self::$properties[$this->Name][$property]))
            return self::$properties[$this->Name][$property];

        if ($this->isClass()) {
            if (property_exists($this->Name, $property)) {
                $propertyInfo = new ReflectionProperty($this->Name, $property);
                self::$properties[$this->Name][$property] = $propertyInfo;
                return $propertyInfo;
            }
        }

        return null;
    }

    public function getMethod(string $method): ?ReflectionMethod
    {
        // use method name in lower case as array key to avoid duplication
        $method = strtolower($method);
        if (isset(self::$methods[$this->Name][$method])) return self::$methods[$this->Name][$method];

        if ($this->isClass()) {
            if (method_exists($this->Name, $method)) {
                $methodInfo = new ReflectionMethod($this->Name, $method);
                self::$methods[$this->Name][$method] = $methodInfo;
                return $methodInfo;
            }
        }

        return null;
    }

    public function isPrimitive(): bool
    {
        $types = ['boolean', 'integer', 'float', 'string'];
        return in_array($this->Name, $types) ? true : false;
    }

    public function isInterface(): bool
    {
        return interface_exists($this->Name);
    }

    public function isClass(): bool
    {
        return class_exists($this->Name);
    }

    public function isGenericType(): bool
    {
        return ($this->isClass() || $this->isInterface()) && $this->arguments ? true : false;
    }

    public function isGenericParameter(): bool
    {
        $types = ['boolean', 'integer', 'float', 'string', 'array', 'object', 'callable'];
        if (in_array($this->Name, $types) || $this->isClass() || $this->isInterface()) {
            return false;
        }

        return true;
    }

    public function isSubclassOf(Type $class): bool
    {
        return is_subclass_of($this->Name, $class->Name);
    }

    public function isEquivalentTo(Type $type): bool
    {
        if ($this == $type) return true;
        if ($this->Name == 'mixed' || $type->Name == 'mixed') return true;
        if ($this->Name == 'object' && $type->isClass()) return true;
        if ($type->Name == 'object' && $this->isClass()) return true;

        return false;
    }

    public function isAssignableFrom(Type $type): bool
    {
        if ($type->isEquivalentTo($this)) return true;

        if ($type->isSubclassOf($this)) {
            return $this->arguments == $type->getGenericArguments() ? true : false;
        }

        if ($this->isInterface()) {
            return in_array($this->Name, $type->getInterfaces());
        }

        return false;
    }

    public function isAssignableTo(Type $type): bool
    {
        if ($this->isEquivalentTo($type)) return true;

        if ($this->isSubclassOf($type)) {
            return $this->arguments == $type->getGenericArguments() ? true : false;
        }

        if ($type->isInterface()) {
            return in_array($type->Name, $this->getInterfaces());
        }

        return false;
    }

    public function isTypeOf($element): bool
    {
        $type = static::getType($element);
        return $this->isAssignableFrom($type);
    }

    public function __toString(): string
    {
        $name = $this->Name;
        $name = $this->isGenericType() ? $name  . '<' . implode(',', $this->arguments) . '>' : $name;
        return $name;
    }

    public static function getType($element): Type
    {
        $typeName = gettype($element);
        if ($typeName == 'object') {
            $className = get_class($element);
            if (method_exists($className, 'gettype')) {
                // use method name in lower case as array key to avoid duplication
                $methodInfo = self::$methods[$className]['gettype'] ?? null;
                if (!$methodInfo) {
                    $methodInfo = new \ReflectionMethod($className, 'gettype');
                    self::$methods[$className]['gettype'] = $methodInfo;
                }
                if ($methodInfo->hasReturnType() && $methodInfo->getReturnType()->getName() == Type::class) {
                    return $element->getType();
                }
            }

            return new Type($className);
        }

        return new Type($typeName);
    }
}
