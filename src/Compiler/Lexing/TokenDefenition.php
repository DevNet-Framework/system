<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Compiler\Lexing;

class TokenDefenition
{
    public string $name;
    public string $pattern;

    public function __construct(string $name, string $pattern)
    {
        $this->name = $name;
        $this->pattern = $pattern;
    }
}