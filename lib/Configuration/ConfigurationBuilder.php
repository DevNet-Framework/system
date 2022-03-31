<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Configuration;

class ConfigurationBuilder
{
    private string $basePath = '/';
    private array $settings = [];

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function setBasePath(string $basePath)
    {
        $this->basePath = $basePath;
    }

    public function addSetting(string $key, $value)
    {
        $this->settings[$key] = $value;
    }

    public function addJsonFile(string $path)
    {
        $fullPath = $this->basePath . "/" . $path;

        if (!file_exists($fullPath)) {
            throw new \Exception("Not found file {$fullPath}");
        }

        $settings = file_get_contents($fullPath);
        $settings = json_decode($settings, true);
        $this->settings = array_merge($this->settings, $settings);
    }

    public function build(): IConfiguration
    {
        return new ConfigurationRoot($this->settings);
    }
}
