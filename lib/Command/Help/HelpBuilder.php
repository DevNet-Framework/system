<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command\Help;

use DevNet\System\Command\CommandLine;
use DevNet\System\IO\ConsoleColor;

class HelpBuilder
{
    private CommandLine $command;
    private ?ConsoleColor $primaryColor = null;
    private ?ConsoleColor $secondaryColor = null;
    private array $layouts = [];
    private int $maxWidth = 0;
    private string $indent = '  ';

    public function __construct(CommandLine $command)
    {
        $this->command = $command;
    }

    public function setColor(ConsoleColor $primaryColor, ?ConsoleColor $secondaryColor = null): void
    {
        $this->primaryColor = $primaryColor;
        $this->secondaryColor = $secondaryColor;
    }

    public function useDefaults(): void
    {
        $this->writeDescription();
        $this->writeUsage();
        $this->writeArguments();
        $this->writeOptions();
        $this->writeCommands();
    }

    public function writeHeading(string $title)
    {
        $this->layouts[] = ['type' => 'heading', 'content' => $title];
    }

    public function writeLine(string $value = '')
    {
        $this->layouts[] = ['type' => 'line', 'content' => $value];
    }

    public function writeRows(array $rows): void
    {
        $lines = [];
        ksort($rows);
        foreach ($rows as $label => $description) {
            $lines[$this->indent . $label] = $description;
            $length = strlen($label);
            if ($length > $this->maxWidth) {
                $this->maxWidth = $length;
            }
        }

        $this->layouts[] = ['type' => 'rows', 'content' => $lines];
    }

    public function writeDescription(): void
    {
        $this->writeHeading('Description:');
        $this->writeLine($this->indent . "{$this->command->Description}");
        $this->writeLine();
    }

    public function writeUsage(): void
    {
        $this->writeHeading("Usage:");
        $usage = $this->indent;

        $parents = [];
        $command = $this->command;

        while ($command->Parent) {
            $parents[] = $command->Parent;
            $command = $command->Parent;
        }

        $parents = array_reverse($parents);

        foreach ($parents as $parent) {
            $usage .= $parent->Name;
            $usage .= ' ';
        }

        $usage .= $this->command->Name;

        if ($this->command->Arguments) {
            $usage .= ' ';
            $usage .= '[arguments]';
        }

        if ($this->command->Commands) {
            $usage .= ' ';
            $usage .= '[command]';
        }

        if ($this->command->Options) {
            $usage .= ' ';
            $usage .= '[options]';
        }

        $this->writeLine($usage);
        $this->writeLine();
    }

    public function writeArguments(): void
    {
        if ($this->command->Arguments) {
            $this->writeHeading("Arguments:");
            $rows = [];
            foreach ($this->command->Arguments as $argument) {
                $rows[$argument->Name] = $argument->Description;
            }
            $this->writeRows($rows);
            $this->writeLine();
        }
    }

    public function writeOptions(): void
    {
        if ($this->command->Options) {
            $this->writeHeading("Options:");
            $rows = [];
            foreach ($this->command->Options as $option) {
                $name = $option->Name;
                if ($option->Alias) {
                    $name .= ', ' . $option->Alias;
                }
                $rows[$name] = $option->Description;
            }
            $this->writeRows($rows);
            $this->writeLine();
        }
    }

    public function writeCommands(): void
    {
        if ($this->command->Commands) {
            $this->writeHeading("Commands:");
            $rows = [];
            foreach ($this->command->Commands as $command) {
                $rows[$command->Name] = $command->Description;
            }
            $this->writeRows($rows);
            $this->writeLine();
        }
    }

    public function build(): HelpResult
    {
        return new HelpResult($this->layouts, $this->maxWidth, $this->primaryColor, $this->secondaryColor);
    }
}
