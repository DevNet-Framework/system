<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Command\Templates\Entity;

use Artister\System\Cli\Parser\CommandParser;
use Artister\System\Runtime\Boot\LauncherProperties;
use Artister\System\StringBuilder;
use Artister\System\ConsoleColor;
use Artister\System\Console;

class Program
{
    public static function main(array $args = [])
    {
        $rootPath = getcwd();
        $className  = null;
        $basePath   = "Models";
        $namespace = LauncherProperties::getNamespace();

        $parser = new CommandParser();
        $parser->addParameter('name');
        $parser->addOption('--directory');
        $arguments = $parser->parse($args);

        $controllerParameter = $arguments->getParameter('name');

        if ($controllerParameter) {
            $className = $controllerParameter->Value;
        }

        if (!$className) {
            Console::foregroundColor(ConsoleColor::Red);
            Console::writeline("Entity name not found, maybe forget to enter the entity name argument");
            Console::writeline("Try \"new --help\" for more information.");
            Console::resetColor();
            exit;
        }

        $directoryOption = $arguments->getOption('--directory');
        if ($directoryOption) {
            $basePath = $directoryOption->Value;
        }

        $path = implode("/", [$rootPath, $basePath]);
        $namespace = implode("\\", [$namespace, $basePath]);

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $result = self::createClass($path, $namespace, $className);

        if ($result) {
            Console::foregroundColor(ConsoleColor::Green);
            Console::writeline("$className.php has been created in $path");
            Console::resetColor();
        }
    }

    public static function createClass($path, $namespace, $className) : bool
    {
        $namespace  = ucwords($namespace, "\\");
        $className  = ucfirst($className);

        $context = new StringBuilder();
        $context->appendLine("<?php");
        $context->appendLine();
        $context->appendLine("namespace {$namespace};");
        $context->appendLine();
        $context->appendLine("use Artister\DevNet\Entity\IEntity;");
        $context->appendLine();
        $context->appendLine("class {$className} implements IEntity");
        $context->appendLine("{");
        $context->appendLine("    private int \$Id;");
        $context->appendLine();
        $context->appendLine("    public function __get(string \$name)");
        $context->appendLine("    {");
        $context->appendLine("        return \$this->\$name;");
        $context->appendLine("    }");
        $context->appendLine();
        $context->appendLine("    public function __set(string \$name, \$value)");
        $context->appendLine("    {");
        $context->appendLine("         \$this->\$name = \$value;");
        $context->appendLine("    }");
        $context->append("}");

        $myfile     = fopen($path."/".$className.".php", "w");
        $size       = fwrite($myfile, $context->__toString());
        $status     = fclose($myfile);

        if ($size && $status) {
            return true;
        }

        return false;
    }
}
