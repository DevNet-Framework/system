<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Command\Parser;

class CommandArguments
{
    public array $Values;
    public array $Parameters;
    public array $Options;

    public function __construct(array $values, array $parameters, array $options)
    {
        $this->Values       = $values;
        $this->Parameters   = $parameters;
        $this->Options      = $options;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function getParameter(string $name) : ?CommandParameter
    {
        return $this->Parameters[strtolower($name)] ?? null;
    }

    public function getOption(string $name) : ?CommandOption
    {
        return $this->Options[strtolower($name)] ?? null;
    }
}