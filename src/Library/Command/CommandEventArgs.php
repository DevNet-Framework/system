<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

use DevNet\System\Event\EventArgs;

class CommandEventArgs extends EventArgs
{
    private ?string $Name;
    private array $Parameters;
    private array $Options;
    private array $Arguments;

    public function getName()
    {
        return $this->Attributes['name'] ?? null;
    }

    public function getArguments()
    {
        return $this->Attributes['arguments'] ?? [];
    }
}
