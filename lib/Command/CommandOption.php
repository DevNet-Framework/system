<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

class CommandOption extends CommandArgument
{
    protected string $alias;

    public function __construct(string $name, string $description = '', string $alias = '', string $value = '')
    {
        parent::__construct($name, $description, $value);

        $this->alias = strtolower($alias);
    }

    public function get_Alias(): string
    {
        return $this->alias;
    }
}
