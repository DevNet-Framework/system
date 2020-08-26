<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Common;

class Overload
{
    private array $Signatures = [];

    public function addSignature(...$Types) : void
    {
        $this->Signatures[] = $Types;
    }

    public function match(array $Parameters) : int
    {
        $Signature = 0;
        foreach ($this->Signatures as $Key => $Types) {
            if (count($Parameters) >= count($Types)) {
                foreach ($Types as $index => $Type) {
                    if (gettype($Parameters[$index]) == $Type || $Parameters[$index] instanceof $Type) {
                        if (count($Parameters) - 1 == $index && count($Parameters) == count($Types)) {
                            return $Key + 1;
                        } else {
                            $Signature = $Key + 1;
                            continue;
                        }
                    } else {
                        break;
                    }
                }
            }
        }

        return $Signature;
    }
}