<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostic\Internal;

use DevNet\System\Diagnostic\IDebugHandler;
use Throwable;

class DebugHandler implements IDebugHandler
{
    public function handle(Throwable $exception): void
    {
        $parser = new DebugParser();
        $error = $parser->parse($exception);
        $trace = $error['trace'];
        $this->renderHtml($error, $trace);
    }

    public function renderHtml(array $error, array $trace): void
    {
        $path = 'views/DebugView.phtml';
        include __DIR__ . '/../Resources/' . $path;
        //require_once $path;
    }

    public function includeStyle()
    {
        $path = 'assets/css/DebugStyle.css';
        include __DIR__ . '/../Resources/' . $path;
    }

    public function render(array $error, array $trace): void
    {
        header('Content-Type: text/plain');

        $head = "%s: %s" . "%s" . PHP_EOL . PHP_EOL . "Uncaught %s" . PHP_EOL . "in %s line: %d" . PHP_EOL . PHP_EOL;
        $template = sprintf($head, $error['error'], $error['code'], $error['message'], $error['class'], $error['file'], $error['line']);
        $template .= "Stack trace:" . PHP_EOL;

        $number = 1;
        $format = "%3d. at %s%s%s()" . PHP_EOL . "     in %s line: %d" . PHP_EOL . PHP_EOL;
        foreach ($trace as $frame) {
            $class = $frame['class'] ?? '';
            $type = $frame['type'] ?? '';
            $function = $frame['function'] ?? '';
            $file = $frame['file'] ?? '';
            $line = $frame['line'] ?? '';

            $template .= sprintf($format, $number, $class, $type, $function, $file, $line);
            $number++;
        }

        echo $template;
    }
}
