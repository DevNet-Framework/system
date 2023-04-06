<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace Devnet\System {

    use DevNet\System\Diagnostics\Debug;
    use DevNet\System\Async\AsyncFunction;
    use DevNet\System\Type;

    /**
     * add async helper.
     */
    function async(callable $action): AsyncFunction
    {
        return new AsyncFunction($action);
    }

    /**
     * add typeOf helper.
     */
    function typeOf(string $typeName, array $typeArguments = []): Type
    {
        return new Type($typeName, $typeArguments);
    }

    /**
     * add debug helper.
     */
    function debug($value = null): Debug
    {
        $debug = Debug::getInstance();
        if (func_num_args()) {
            $time = \DateTime::createFromFormat('U.u', microtime(TRUE));
            $debug->write('[' . $time->format('H:i:s.v') . '] ');
            $debug->writeLine($value, 'Debug');
            $debug->indent();
            $debug->write('at ');
            $debug->caller(1);
            $debug->unindent();
        }
        return $debug;
    }
}

namespace {

    use DevNet\System\Diagnostics\Debug;
    use DevNet\System\Async\AsyncFunction;
    use DevNet\System\Type;

    /**
     * add async helper.
     */
    if (!function_exists("async")) {
        function async(callable $action): AsyncFunction
        {
            return new AsyncFunction($action);
        }
    }

    /**
     * add typeOf helper.
     */
    if (!function_exists("typeOf")) {
        function typeOf(string $typeName, array $typeArguments = []): Type
        {
            return new Type($typeName, $typeArguments);
        }
    }

    /**
     * add debug helper.
     */
    if (!function_exists("debug")) {
        function debug($value = null): Debug
        {
            $debug = Debug::getInstance();
            if (func_num_args()) {
                $time = \DateTime::createFromFormat('U.u', microtime(TRUE));
                $debug->write('[' . $time->format('H:i:s.v') . '] ');
                $debug->writeLine($value, 'Debug');
                $debug->indent();
                $debug->write('at ');
                $debug->caller(1);
                $debug->unindent();
            }
            return $debug;
        }
    }
}
