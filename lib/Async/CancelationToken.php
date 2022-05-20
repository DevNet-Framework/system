<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use Closure;
use DevNet\System\Exceptions\PropertyException;

class CancelationToken
{
    private CancelationSource $source;
    private Closure $action;
    private bool $isCancellationRequested = false;

    public function __get(string $name)
    {
        if (in_array($name, ['Source', 'Action', 'IsCancellationRequested'])) {
            $property = lcfirst($name);
            return $this->$property;
        }
        
        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct($source)
    {
        $this->Source = $source;
    }

    public function register(Closure $action)
    {
        $this->action = $action;
    }
}
