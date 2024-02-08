<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Configuration;

interface IConfiguration
{
    public function getValue(string $key);

    public function getSection(string $key): IConfiguration;

    public function getChildren(): array;
}
