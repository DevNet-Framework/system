<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

class CommandOption extends CommandArgument
{
    protected string $alias;

    public function __construct(string $name, ?string $description = null, ?string $alias = null, $value = null)
    {
        parent::__construct($name, $description, $value);

        $this->alias = strtolower((string) $alias);
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
