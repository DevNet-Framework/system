<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command\Parsing;

use DevNet\System\Command\CommandArgument;
use DevNet\System\Command\CommandOption;

class Parser
{
    private array $options   = [];
    private array $arguments = [];

    public function addArgument(CommandArgument $argument): void
    {
        $this->arguments[] = $argument;
    }

    public function addOption(CommandOption $option): void
    {
        $this->options[] = $option;
    }

    public function parse(array $args): ParseResult
    {
        $parsedArguments = [];
        $parsedOptions = [];
        $arguments = $this->arguments;
        $options = $this->options;

        while ($args != []) {
            $match = false;
            $token  = $args[0] ?? '';
            foreach ($options as $index => $option) {
                if ($token == $option->getName() || $token == $option->getAlias()) {
                    if ($option->getValue() !== null) {
                        $option->setValue($args[1] ?? '');
                        array_shift($args);
                    }
                    $parsedOptions[$option->getName()] = $option;
                    unset($options[$index]);
                    array_shift($args);
                    $match = true;
                    break;
                }
            }

            if (!$match) {
                if ($arguments) {
                    foreach ($arguments as $index => $argument) {
                        $argument->setValue($token);
                        $parsedArguments[$argument->getName()] = $argument;
                        unset($arguments[$index]);
                        array_shift($args);
                        break;
                    }
                } else {
                    break;
                }
            }
        }

        return new ParseResult($parsedArguments, $parsedOptions, $args);
    }
}