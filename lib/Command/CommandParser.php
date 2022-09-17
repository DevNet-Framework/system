<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

class CommandParser
{
    private array $options   = [];
    private array $arguments = [];

    public function addArgument(CommandArgument $argument): void
    {
        $this->arguments[] = $argument;
    }

    public function getArgument(string $name): ?CommandArgument
    {
        if (!$name) {
            return null;
        }

        $name = strtolower($name);
        foreach ($this->arguments as $argument) {
            if (strtolower($argument->getName()) == $name) {
                return $argument;
            }
        }

        return null;
    }

    public function addOption(CommandOption $option): void
    {
        $this->options[] = $option;
    }

    public function getOption(string $name): ?CommandOption
    {
        if (!$name) {
            return null;
        }

        $name = strtolower($name);
        foreach ($this->options as $option) {
            if (strtolower((string)$option->getName()) == $name || strtolower((string)$option->getAlias()) == $name) {
                return $option;
            }
        }

        return null;
    }

    public function parse(array $args): ?CommandEventArgs
    {
        $inputs     = $args;
        $arguments  = $this->arguments;
        $parameters = [];

        while ($inputs != []) {
            $token  = $inputs[0] ?? '';
            $option = $this->getOption($token);

            if ($option) {
                array_shift($inputs);
                $nextToken  = $inputs[0] ?? '';
                $nextOption = $this->getOption($nextToken);
                if ($nextOption) {
                    $parameters[$option->getName()] = $option;
                } else {
                    $option->setValue($nextToken);
                    $parameters[$option->getName()] = $option;
                    array_shift($inputs);
                }
            } else {
                $argument = $arguments[0] ?? null;
                if ($argument) {
                    $argument->setValue($token);
                    $parameters[$argument->getName()] = $argument;
                    array_shift($arguments);
                    array_shift($inputs);
                } else {
                    break;
                }
            }
        }

        if ($inputs) {
            return null;
        }

        $eventArgs = new CommandEventArgs($parameters, $args);

        return $eventArgs;
    }
}
