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
    private array $Options   = [];
    private array $Arguments = [];

    public function addArgument(CommandArgument $argument): void
    {
        $this->Arguments[] = $argument;
    }

    public function addOption(CommandOption $option): void
    {
        $this->Options[] = $option;
    }

    public function getArgument(string $name): ?CommandArgument
    {
        if (!$name) {
            return null;
        }

        $name = strtolower($name);
        foreach ($this->Arguments as $argument) {
            if (strtolower($argument->Name) == $name) {
                return $argument;
            }
        }

        return null;
    }

    public function getOption(string $name): ?CommandOption
    {
        if (!$name) {
            return null;
        }

        $name = strtolower($name);
        foreach ($this->Options as $option) {
            if (strtolower($option->Name) == $name || strtolower($option->Alias) == $name) {
                return $option;
            }
        }

        return null;
    }

    public function parse(array $args): ?CommandEventArgs
    {
        $inputs     = $args;
        $arguments  = $this->Arguments;
        $parameters = [];

        while ($inputs != []) {
            $token  = $inputs[0] ?? '';
            $option = $this->getOption($token);

            if ($option) {
                array_shift($inputs);
                $nextToken  = $inputs[0] ?? '';
                $nextOption = $this->getOption($nextToken);
                if ($nextOption) {
                    $parameters[$option->Name] = $option;
                } else {
                    $option->Value = $nextToken;
                    $parameters[$option->Name] = $option;
                    array_shift($inputs);
                }
            } else {
                $argument = $arguments[0] ?? null;
                if ($argument) {
                    $argument->Value = $token;
                    $parameters[$argument->Name] = $argument;
                    array_shift($arguments);
                    array_shift($inputs);
                } else {
                    break;
                }
            }
        }

        // if there are remaining inputs, it means doesn't match.
        if ($inputs) {
            return null;
        }

        return new CommandEventArgs($parameters);
    }
}
