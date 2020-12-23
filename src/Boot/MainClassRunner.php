<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Boot;

class MainClassRunner
{
    private string $MainClass;
    private array $Args;

    public function __construct($mainClass, $args)
    {
        $this->MainClass = $mainClass;
        $this->Args = $args;
    }
    
    public function run() : void
    {
        if (!class_exists($this->MainClass))
        {
            throw new \Exception("Main class does not exist or not configured yet");
        }

        if (!method_exists($this->MainClass, 'main'))
        {
            throw new \Exception("Main Method does not exist or entry point not configured yet");
        }

        $this->MainClass::main($this->Args);
    }
}
