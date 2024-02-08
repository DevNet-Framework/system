<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Dependency;

interface IServiceProvider
{
    /**
     * Find and return entry of the provider by its service type or throw an exception if not found.
     */
    public function getService(string $serviceType);

    /**
     * Check if the provider can return an entry for the given serviceType.
     */
    public function contains(string $serviceType);
}
