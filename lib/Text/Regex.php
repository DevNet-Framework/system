<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Text;

class Regex
{
    public static function match(string $pattern, string $subject, int $offset = 0, int $flags = 0): ?array
    {
        $matches = null;
        preg_match($pattern, $subject, $matches, $flags, $offset);
        return $matches;
    }

    public static function replace(string $pattern, string $replacement, string $subject, int $limite = -1): ?string
    {
        return preg_replace($pattern, $replacement, $subject, $limite);
    }

    public static function split(string $pattern, string $subject, int $limite = -1, int $flags = 0): ?array
    {
        $chunks = preg_split($pattern, $subject, $limite, $flags);
        if (!$chunks) {
            $chunks = null;
        }
        return $chunks;
    }
}
