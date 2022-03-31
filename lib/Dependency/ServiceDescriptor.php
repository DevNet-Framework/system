<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Dependency;

use Closure;
use DevNet\System\Exceptions\ClassException;
use DevNet\System\Exceptions\PropertyException;

class ServiceDescriptor
{
    public const Singleton = 1;
    public const Transient = 2;

    private int $lifetime;
    private string $serviceType;
    private ?string $implimentationType = null;
    private ?object $implementationInstance = null;
    private ?Closure $implimentationFactory = null;

    public function __get(string $name)
    {
        if (in_array($name, ['Lifetime', 'ServiceType', 'ImplimentationType', 'ImplementationInstance', 'ImplimentationFactory'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
    }

    public function __construct(int $lifetime, string $serviceType, $service)
    {
        switch ($service) {
            case is_callable($service):
                $this->describeFactory($lifetime, $serviceType, $service);
                break;
            case is_object($service):
                $this->describeInstance($lifetime, $serviceType, $service);
                break;
            case is_string($service):
                $this->describeType($lifetime, $serviceType, $service);
                break;
            default:
                throw new \Exception("incomplatible type, it must be of type object, or string of class type, or a callable factory");
                break;
        }
    }

    public function describeInstance(int $lifetime, string $serviceType, object $implementationInstance): void
    {
        if (!$implementationInstance instanceof $serviceType) {
            throw new ClassException("The given service: " . get_class($implementationInstance) . " is not compatible with the declared type: {$serviceType}");
        }

        $this->lifetime               = $lifetime;
        $this->serviceType            = $serviceType;
        $this->implementationInstance = $implementationInstance;
    }

    public function describeType(int $lifetime, string $serviceType, string $implimentationType): void
    {
        if (!class_exists($serviceType) && !interface_exists($serviceType)) {
            throw new ClassException("Can not find declared service type: {$serviceType}");
        }

        if (!class_exists($implimentationType)) {
            throw new ClassException("Can not find declared service type: {$implimentationType}");
        }

        $interfaces = class_implements($implimentationType);
        if (!in_array($serviceType, $interfaces) && $serviceType != $implimentationType) {
            throw new ClassException("The given service: {$implimentationType} is not compatible with the declared type: {$serviceType}");
        }

        $this->lifetime           = $lifetime;
        $this->serviceType        = $serviceType;
        $this->implimentationType = $implimentationType;
    }

    public function describeFactory(int $lifetime, string $serviceType, Closure $implimentationFactory): void
    {
        if (!class_exists($serviceType) && !interface_exists($serviceType)) {
            throw new ClassException("Can not find declared service type: {$serviceType}");
        }

        $this->lifetime              = $lifetime;
        $this->serviceType           = $serviceType;
        $this->implimentationFactory = $implimentationFactory;
    }
}
