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
    public function __construct(ClassLoader $loader)
    {
        static::$classLoader = $loader;
        static::$rootDirectory = $loader->getRoot();
    }

    public function launch(array $args = [], ?string $mainClass = null): int
    {
        static::$classLoader->register();
        self::$arguments = $args;
        if ($mainClass) {
            static::$entryPoint = $mainClass;
        }
        $runner = new MainMethodRunner(static::$entryPoint);
        return $runner->run($args);
    }

    public static function initialize(string $projectPath): ?static
    {
        if (!file_exists($projectPath)) {
            return null;
        }
        
        $projectFile = simplexml_load_file($projectPath);
        if (!$projectFile) {
            return null;
        }

        $root = dirname($projectPath);

        static::$entryPoint = $projectFile->Properties->EntryPoint ?? 'Application\\Program';
        $packages = $projectFile->Dependencies->Package ?? [];
        
        // load local packages including composer
        foreach ($packages as $package) {
            $include = (string)$package->attributes()->include;
            if (file_exists($root. '/' . $include)) {
                require $root . '/' . $include;
            }
        }

        return new static(new ClassLoader($root));
    }
}
