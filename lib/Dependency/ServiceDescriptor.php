<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Dependency;

use DevNet\System\Exceptions\ArgumentException;
use DevNet\System\Exceptions\ClassException;
use DevNet\System\Exceptions\TypeException;
use DevNet\System\ObjectTrait;
use ReflectionFunction;
use Closure;

class ServiceDescriptor
{
    use ObjectTrait;

    public const Singleton = 1;
    public const Transient = 2;

    private int $lifetime;
    private string $serviceType;
    private ?string $implimentationType = null;
    private ?object $implementationInstance = null;
    private ?Closure $implimentationFactory = null;

    public function __construct(int $lifetime, $service)
    {
        $this->lifetime = $lifetime;

        switch ($service) {
            case is_object($service):
                if ($service instanceof Closure) {
                    $reflector = new ReflectionFunction($service);
                    if (!$reflector->hasReturnType()) {
                        throw new TypeException("The service factory must have a return type", 0, 1);
                    }
                    $this->serviceType = $reflector->getReturnType()->getName();
                    $this->implimentationFactory = $service;
                } else {
                    $this->serviceType = get_class($service);
                    $this->implementationInstance = $service;
                }
                break;
            case is_string($service):
                if (!class_exists($service)) {
                    throw new ClassException("Could not find service class: {$service}", 0, 1);
                }
                $this->serviceType = $service;
                $this->implimentationType = $service;
                break;
            default:
                throw new ArgumentException(static::class . "::__construct() The argument #3 must be of type string, object or closure", 0, 1);
                break;
        }
    }

    public function get_Lifetime(): int
    {
        return $this->lifetime;
    }

    public function get_ServiceType(): string
    {
        return $this->serviceType;
    }

    public function get_ImplimentationType(): ?string
    {
        return $this->implimentationType;
    }

    public function get_ImplementationInstance(): ?object
    {
        return $this->implementationInstance;
    }

    public function get_ImplimentationFactory(): ?Closure
    {
        return $this->implimentationFactory;
    }
}
