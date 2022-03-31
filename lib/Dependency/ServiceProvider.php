<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Dependency;

class ServiceProvider implements IServiceProvider
{
    private array $instanceServices = [];
    private IserviceCollection $serviceCollection;

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

                if ($serviceDescriptor->ImplimentationType) {
                    if (isset($this->instanceServices[$serviceType])) {
                        if ($serviceDescriptor->Lifetime == 1) {
                            return $this->instanceServices[$serviceType];
                        }

                        if ($serviceDescriptor->Lifetime == 2) {
                            return clone $this->instanceServices[$serviceType];
                        }
                    }

                    $instance = Activator::CreateInstance($serviceDescriptor->ImplimentationType, $this);
                    $this->instanceServices[$serviceType] = $instance;
                    return $instance;
                }

                if ($serviceDescriptor->ImplimentationFactory) {
                    if (isset($this->instanceServices[$serviceType])) {
                        if ($serviceDescriptor->Lifetime == 1) {
                            return $this->instanceServices[$serviceType];
                        }

                        if ($serviceDescriptor->Lifetime == 2) {
                            return clone $this->instanceServices[$serviceType];
                        }
                    }
                    $factory = $serviceDescriptor->ImplimentationFactory;
                    $instance = $factory($this);

                    if (!$instance instanceof $serviceType) {
                        throw new \Exception("Return value of factory function must be of the type '$serviceType'");
                    }

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
