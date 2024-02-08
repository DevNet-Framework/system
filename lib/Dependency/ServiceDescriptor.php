<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Dependency;

use DevNet\System\Exceptions\ArgumentException;
use DevNet\System\Exceptions\ClassException;
use DevNet\System\Exceptions\TypeException;
use DevNet\System\PropertyTrait;
use DevNet\System\Type;
use ReflectionFunction;
use Closure;

class ServiceDescriptor
{
    use PropertyTrait;

    public const Singleton = 1;
    public const Transient = 2;

    private int $lifetime;
    private string $serviceType;
    private ?string $implementationType = null;
    private ?object $implementationInstance = null;
    private ?Closure $implementationFactory = null;

    public function __construct(int $lifetime, string $serviceType, $service = null)
    {
        $this->lifetime = $lifetime;
        $this->serviceType = $serviceType;

        if (!$service) {
            $service = $serviceType;
        }

        $serviceType = new Type($serviceType);

        switch ($service) {
            case is_string($service):
                if (!class_exists($service)) {
                    throw new ClassException("Could not find service class: {$service}", 0, 1);
                }
                // The registered service must be assignable to the declared service type
                if (!$serviceType->isAssignableFrom(new Type($service))) {
                    throw new TypeException("The registered service is not compatible with the declared type {$serviceType}", 0, 1);
                }
                $this->implementationType = $service;
                break;
            case is_object($service):
                if ($service instanceof Closure) {
                    $reflector = new ReflectionFunction($service);
                    if (!$reflector->hasReturnType()) {
                        throw new TypeException("The service factory must have a return type", 0, 1);
                    }

                    // The registered service must be assignable to the factory retune type and to the declared service type
                    // and the declared service type must be assignable to and from the factory return type
                    // because both can be concrete or abstract type of the registered service.
                    $type = new Type($reflector->getReturnType()->getName());
                    if (!$serviceType->isAssignableFrom($type) && !$serviceType->isAssignableTo($type)) {
                        throw new TypeException("The registered service is not compatible with the declared type {$serviceType}", 0, 1);
                    }
                    $this->implementationFactory = $service;
                } else {
                    // The registered service must be assignable to the declared service type
                    if (!$serviceType->isTypeOf($service)) {
                        throw new TypeException("The registered service is not compatible with the declared type {$serviceType}", 0, 1);
                    }
                    $this->implementationInstance = $service;
                }
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

    public function get_ImplementationType(): ?string
    {
        return $this->implementationType;
    }

    public function get_ImplementationInstance(): ?object
    {
        return $this->implementationInstance;
    }

    public function get_ImplementationFactory(): ?Closure
    {
        return $this->implementationFactory;
    }
}
