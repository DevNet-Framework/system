<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

use DevNet\System\Exceptions\PropertyException;

interface ICommandArgument
{
    /**
     * This method must retun the following properties.
     * @return string $Name
     * @return mixed $Value
     * and must throw an exception if the property doesn't exist
     * @throws PropertyException
     */
    public function __get(string $name);

    public function setName(string $name): void;

    public function setValue($value): void;
}
