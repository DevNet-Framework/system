<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
