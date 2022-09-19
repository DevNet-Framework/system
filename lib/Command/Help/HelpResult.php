<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command\Help;

use DevNet\System\IO\Console;

class HelpResult
{
    private array $layouts = [];
    private int $primaryColor = 0;
    private int $secondaryColor = 0;

    public function __construct(array $layouts, int $primaryColor = 0, int $secondaryColor = 0)
    {
        $this->layouts = $layouts;
        $this->primaryColor = $primaryColor;
        $this->secondaryColor = $secondaryColor;
    }

    public function write(): void
    {
        foreach ($this->layouts as $layout) {
            switch ($layout['type']) {
                case 'heading':
                    if ($this->primaryColor) {
                        Console::foregroundColor($this->primaryColor);
                    }
                    Console::writeLine($layout['content']);
                    Console::resetColor();
                    break;
                case 'rows':
                    foreach ($layout['content'] as $name => $description) {
                        if ($this->secondaryColor) {
                            Console::foregroundColor($this->secondaryColor);
                        }
                        Console::write($name);
                        Console::resetColor();
                        Console::writeLine($description);
                    }
                    break;
                case 'line':
                    Console::writeLine($layout['content']);
                    break;
            }
        }
    }
}
