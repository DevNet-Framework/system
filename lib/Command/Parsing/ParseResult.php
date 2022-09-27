<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command\Parsing;

class ParseResult
{
    private array $arguments = [];
    private array $options = [];
    private array $tokens = [];

    public function __construct(array $arguments = [], array $options = [], array $tokens = [])
    {
        $this->arguments = $arguments;
        $this->options = $options;
        $this->tokens = $tokens;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getUnparsedTokens(): array
    {
        return $this->tokens;
    }
}
