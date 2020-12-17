<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Command\Parser;

class CommandParser
{
    private array $Parameters = [];
    private array $Options = [];

    public function addParameter(string $name)
    {
        $this->Parameters[] = $name;
    }

    public function addOption(string $name)
    {
        $this->Options[strtolower($name)] = $name;
    }

    public function parse(array $args)
    {
        $params     = $this->Parameters;
        $arguments  = $args;
        $parameters = [];
        $options    = [];

        do {
            $token = array_shift($args);
            $normalToken = $token ? strtolower($token) : null;

            if (isset($this->Options[$normalToken])) {

                $nextToken = $args[0] ?? null;
                $normlNextToken = $nextToken ? strtolower($nextToken) : null;

                if (!isset($this->Options[$normlNextToken])) {
                    $options[$normalToken] = new CommandOption($token, $nextToken);
                    array_shift($args);
                } else {
                    $options[$normalToken] = new CommandOption($token, null);
                }
            } else {
                $paramName = $params[0] ?? null;
                if ($paramName) {
                    $parameters[strtolower($paramName)] = new CommandParameter($paramName, $token);
                    array_shift($params);
                }
            }

        } while ($args != []);

        return new CommandArguments($arguments, $parameters, $options);
    }
}