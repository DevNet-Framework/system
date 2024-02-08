<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Dependency;

class ServiceProvider implements IServiceProvider
{
    private array $instanceServices = [];
    private IServiceCollection $serviceCollection;

    public function __construct(IServiceCollection $serviceCollection)
    {
        $this->serviceCollection = $serviceCollection;
    }

    /**
     * Find and return entry of the provider by its service type or null if not found.
     */
    public function getService(string $serviceType): ?object
    {
        foreach ($this->serviceCollection as $serviceDescriptor) {
            if ($serviceType == $serviceDescriptor->ServiceType) {
                if ($serviceDescriptor->ImplementationInstance) {
                    if (isset($this->instanceServices[$serviceType])) {
                        if ($serviceDescriptor->Lifetime == 1) {
                            return $this->instanceServices[$serviceType];
                        }

                        if ($serviceDescriptor->Lifetime == 2) {
                            return clone $this->instanceServices[$serviceType];
                        }
                    }
                    $this->instanceServices[$serviceType] = $serviceDescriptor->ImplementationInstance;
                    return $serviceDescriptor->ImplementationInstance;
                }

                if ($serviceDescriptor->ImplementationType) {
                    if (isset($this->instanceServices[$serviceType])) {
                        if ($serviceDescriptor->Lifetime == 1) {
                            return $this->instanceServices[$serviceType];
                        }

                        if ($serviceDescriptor->Lifetime == 2) {
                            return clone $this->instanceServices[$serviceType];
                        }
                    }

                    $instance = Activator::CreateInstance($serviceDescriptor->ImplementationType, $this);
                    $this->instanceServices[$serviceType] = $instance;
                    return $instance;
                }

                if ($serviceDescriptor->ImplementationFactory) {
                    if (isset($this->instanceServices[$serviceType])) {
                        if ($serviceDescriptor->Lifetime == 1) {
                            return $this->instanceServices[$serviceType];
                        }

                        if ($serviceDescriptor->Lifetime == 2) {
                            return clone $this->instanceServices[$serviceType];
                        }
                    }
                    $factory = $serviceDescriptor->ImplementationFactory;
                    $instance = $factory($this);

                    $this->instanceServices[$serviceType] = $instance;
                    return $instance;
                }
            }
        }

        return null;
    }

    /**
     * Check if the provider can return an entry for the given serviceType.
     */
    public function contains(string $serviceType): bool
    {
        if (isset($this->instanceServices[$serviceType])) {
            return true;
        } else {
            $service = $this->getService($serviceType);
            if ($service) {
                return true;
            }
        }

        return false;
    }
}
