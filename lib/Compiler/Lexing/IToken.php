<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Lexing;

use DevNet\System\Compiler\IComponent;

interface IToken extends IComponent
{
    const SKIPPED = 'SKIPPED';
    const UNKNOWN = 'UNKNOWN';
    const EOI     = 'EOI';

    public function getValue(): string;
}
