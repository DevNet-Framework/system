<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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

    public static function replace(string $pattern, string $replacement, string $subject, int $limit = -1): ?string
    {
        return preg_replace($pattern, $replacement, $subject, $limit);
    }

    public static function split(string $pattern, string $subject, int $limit = -1, int $flags = 0): ?array
    {
        $chunks = preg_split($pattern, $subject, $limit, $flags);
        if (!$chunks) {
            $chunks = null;
        }
        return $chunks;
    }
}
