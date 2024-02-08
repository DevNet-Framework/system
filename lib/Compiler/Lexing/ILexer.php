<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Lexing;

interface ILexer
{
    public function scan(string $text);

    public function advance();

    public function getToken(): IToken;
}
