<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use ReflectionFunction;
use Closure;

class Action
{
    private ReflectionFunction $MethodInfo;
    private array $StaticVariables = [];
    private string $Syntax = '';

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(callable $action)
    {
        $action = Closure::fromCallable($action);
        $this->MethodInfo = new ReflectionFunction($action);
    }

    public function invokeArgs(array $args = [])
    {
        return $this->MethodInfo->invokeArgs($args);
    }

    public function __invoke(...$args)
    {
        return $this->invokeArgs($args);
    }

    public function  __serialize(): array
    {
        $staticVariables = $this->MethodInfo->getStaticVariables();

        $fileName = $this->MethodInfo->getFileName();
        $startLine = $this->MethodInfo->getStartLine() - 1; // adjustment by - 1, because line 1 is in inedx 0
        $endLine = $this->MethodInfo->getEndLine();
        $length = $endLine - $startLine;

        $source = file($fileName, FILE_IGNORE_NEW_LINES);
        $lines = array_slice($source, $startLine, $length);
        $syntax = implode(PHP_EOL, $lines);
        preg_match("/(?i)function(.|\s)*\}/", $syntax, $matches);
        if (!$matches[0]) {
            throw new \Exception("Syntax Error, Closure can't be serialized");
        }
        $syntax = $matches[0] . ";";

        return ['StaticVariables' => $staticVariables, 'Syntax' => $syntax];
    }

    public function __unserialize(array $data): void
    {
        $this->StaticVariables = $data['StaticVariables'];
        $this->Syntax = $data['Syntax'];

        extract($this->StaticVariables);
        $closure = eval("return " . $this->Syntax);
        $this->__construct($closure);
        
        $this->Syntax = '';
        $this->StaticVariables = [];
    }
}
