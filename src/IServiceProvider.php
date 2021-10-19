<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

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
