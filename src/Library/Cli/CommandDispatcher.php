<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Cli;

use Closure;

class CommandDispatcher
{
    private array $Commands = [];

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function addCommand(Closure $builder)
    {
        $command = new Command();
        $builder($command);
        $this->Commands[] = $command;
    }

    public function addOption(Closure $builder)
    {
        $command = new Command();
        $builder($command);
        $this->Commands[] = $command;
    }

    public function invoke(array $args) : bool
    {
        $commandName = array_shift($args);

        foreach ($this->Commands as $command) {
            //match the command name
            if ($command->getName() == $commandName) {
                $command->Execute($args);
                return true;
            }
        }

        return false;
    }
}