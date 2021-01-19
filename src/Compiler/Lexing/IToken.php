<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Compiler\Lexing;

use Artister\System\Compiler\IComponent;

interface IToken extends IComponent
{
    const SKIPPED = 'SKIPPED';
    const UNKNOWN = 'UNKNOWN';
    const EOI     = 'EOI';
    
    public function getValue() : string;
}