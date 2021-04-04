<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Lexing;

use DevNet\System\Compiler\IComponent;

interface IToken extends IComponent
{
    const SKIPPED = 'SKIPPED';
    const UNKNOWN = 'UNKNOWN';
    const EOI     = 'EOI';
    
    public function getValue() : string;
}
