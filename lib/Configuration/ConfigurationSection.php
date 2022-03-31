<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Configuration;

class ConfigurationSection implements IConfiguration
{
    private IConfiguration $root;
    private string $path;

    public function __construct(IConfiguration $root, string $path)
    {
        $this->root = $root;
        $this->path = $path;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function getValue(string $key)
    {
        return $this->root->getValue($this->path . ":" . $key);
    }

    public function getSection(string $key): IConfiguration
    {
        return new ConfigurationSection($this->root, $this->path . ":" . $key);
    }

    public function getChildren(): array
    {
        return $this->root->getChildren($this->path);
    }
}
