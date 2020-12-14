<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Command\Handlers;

use Artister\System\Cli\ICommandHandler;
use Artister\System\Event\EventArgs;
use Artister\System\ConsoleColor;
use Artister\System\Console;

class NewCommandHandler implements ICommandHandler
{
    public function execute(object $sender, EventArgs $event) : void
    {
        $arguments  = $event->getAttribute('arguments');
        $template   = $arguments->getParameter('template');
        $help       = $arguments->getOption('--help');
        
        if ($help)
        {
            $this->showHelp();
        }
        
        if (!$template) {
            Console::foreGroundColor(ConsoleColor::Red);
            Console::writeline("Template not found");
            Console::writeline();
            Console::resetColor();
            exit;
        }

        $args = $arguments->Values;
        array_shift($args);

        $templateName = $template->Value ?? '';
        $templateName = strtolower($templateName);

        switch ($templateName) {
            case 'web':
                \Artister\System\Command\Templates\Web\Program::main($args);
                break;
            case 'console':
                \Artister\System\Command\Templates\Console\Program::main($args);
                break;
            case 'controller':
                \Artister\System\Command\Templates\Controller\Program::main($args);
                break;
            case 'entity':
                \Artister\System\Command\Templates\Entity\Program::main($args);
                break;
        }
    }

    public function showHelp()
    {
        Console::writeline("DevNet SDK command line interpreter");
        Console::writeline();
        Console::writeline("Usage:");
        Console::writeline("  new [template] [arguments] [options]");
        Console::writeline();
        Console::writeline("Options:");
        Console::writeline("  --help  Displays help for this command.");
        Console::writeline();
        Console::writeline("templates:");
        Console::writeline("  console     Console Applicatinon");
        Console::writeline("  web         Web Applicatinon");
        Console::writeline("  controller  Controller Class");
        Console::writeline("  entity      Entity Class");
        Console::writeline();
        exit;
    }
}