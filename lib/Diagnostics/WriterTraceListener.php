<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

class WriterTraceListener extends TraceListener
{
    protected bool $Indent;

    public function write($value, ?string $category = null): void
    {
        $message = '';
        $type = gettype($value);
        switch ($type) {
            case 'NULL':
                $message = 'null';
                break;
            case 'boolean':
                if ($value) {
                    $message = 'true';
                } else {
                    $message = 'false';
                }
                break;
            case 'integer':
            case 'double':
            case 'string':
                $message = (string) $value;
                break;
            case 'array':
                $message = 'array [' . count($value) . ']';
                break;
            case 'object':
                $message = get_class($value);
                break;
            case 'resource':
                $message = 'resource';
                break;
            default:
                $message = 'unknown type';
                break;
        }

        if ($this->NeedIndent && $this->IndentLevel) {
            $spaces = '';
            $indent = $this->IndentLevel * $this->IndentSize;
            for ($i = 0; $i < $indent; $i++) {
                $spaces .= ' ';
            }

            $message = $spaces . $message;
        }

        if ($category) {
            $message = $category . ': ' . $message;
        }

        $this->Writer->write($message);
        $this->NeedIndent = false;
    }

    public function writeLine($value, ?string $category = null): void
    {
        $this->write($value, $category);
        $this->Writer->write(PHP_EOL);
        $this->NeedIndent = true;
    }

    public function caller(int $skipFrames = 0): void
    {
        // adapt the frame level to the outer scope by one step.
        $skipFrames++;
        $stack  = new StackTrace($skipFrames);
        $frame  = $stack->getFrame(0);
        $file   = $frame->FileName . ':' . $frame->LineNumber;
        $frame  = $stack->getFrame(1);
        $caller = $frame->FunctionName . '()';
        if ($frame->ClassName) {
            $caller = $frame->ClassName . '::' . $caller;
        }

        $this->writeLine($caller . ' in ' . $file);
    }
}
