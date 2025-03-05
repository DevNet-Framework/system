<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command\Help;

use DevNet\System\IO\Console;
use DevNet\System\IO\ConsoleColor;

class HelpResult
{
    private array $layouts = [];
    private ?ConsoleColor $primaryColor = null;
    private ?ConsoleColor $secondaryColor = null;
    private int $maxWidth = 0;

    public function __construct(array $layouts, int $maxWidth, ?ConsoleColor $primaryColor = null, ?ConsoleColor $secondaryColor = null)
    {
        $this->layouts = $layouts;
        $this->maxWidth = $maxWidth;
        $this->primaryColor = $primaryColor;
        $this->secondaryColor = $secondaryColor;
    }

    public function write(): void
    {
        foreach ($this->layouts as $layout) {
            switch ($layout['type']) {
                case 'heading':
                    if ($this->primaryColor) {
                        Console::$ForegroundColor = $this->primaryColor;
                    }
                    Console::writeLine($layout['content']);
                    Console::resetColor();
                    break;
                case 'rows':
                    foreach ($layout['content'] as $name => $description) {
                        if ($this->secondaryColor) {
                            Console::$ForegroundColor = $this->secondaryColor;
                        }
                        $length = strlen($name);
                        $space = str_repeat(" ", $this->maxWidth - $length + 4);
                        Console::write($name . $space);
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
