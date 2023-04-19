<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace Devnet\System {

    use DevNet\System\Async\AsyncFunction;
    use DevNet\System\Async\IAwaitable;
    use DevNet\System\Diagnostics\Debug;
    use DevNet\System\Exceptions\ArrayException;
    use DevNet\System\Type;
    use Fiber;

    function async(callable $action): AsyncFunction
    {
        return new AsyncFunction($action);
    }

    function await(IAwaitable $awaitable): mixed
    {
        $awaitable = Fiber::suspend($awaitable);
        return $awaitable->getAwaiter()->getResult();
    };

    function typeOf(string $typeName, array $typeArguments = []): Type
    {
        try {
            $type = new Type($typeName, $typeArguments);
        } catch (ArrayException $exception) {
            throw new ArrayException($exception->getMessage(), $exception->getCode(), 1);
        }

        return $type;
    }

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

    use DevNet\System\Async\AsyncFunction;
    use DevNet\System\Async\IAwaitable;
    use DevNet\System\Diagnostics\Debug;
    use DevNet\System\Exceptions\ArrayException;
    use DevNet\System\Type;

    if (!function_exists("async")) {
        function async(callable $action): AsyncFunction
        {
            return new AsyncFunction($action);
        }
    }

    if (!function_exists("await")) {
        function await(IAwaitable $awaitable): mixed
        {
            $awaitable = Fiber::suspend($awaitable);
            return $awaitable->getAwaiter()->getResult();
        };
    }

    if (!function_exists("typeOf")) {
        function typeOf(string $typeName, array $typeArguments = []): Type
        {
            try {
                $type = new Type($typeName, $typeArguments);
            } catch (ArrayException $exception) {
                throw new ArrayException($exception->getMessage(), $exception->getCode(), 1);
            }

            return $type;
        }
    }

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
