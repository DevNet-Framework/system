<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Runtime;

class Launcher extends LauncherProperties
{
    public function __construct(string $rootDirectory, array $autoloadMap = [])
    {
        static::$classLoader = new ClassLoader($rootDirectory, $autoloadMap);
        static::$rootDirectory = $rootDirectory;
    }

    public function launch(string $mainClass, array $args = []): int
    {
        static::$classLoader->register();
        static::$entryPoint = $mainClass;

        if (!$args) {
            $args = $GLOBALS['argv'] ?? [];
            array_shift($args);
        }

        self::$arguments = $args;
        $runner = new MainMethodRunner($mainClass, $args);

        return $runner->run();
    }
}
