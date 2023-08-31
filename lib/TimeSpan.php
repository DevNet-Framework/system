<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

class TimeSpan
{
    use Tweak;

    private float $seconds = 0;

    public function __construct(int $days = 0, int $hours = 0, int $minutes = 0, float $seconds = 0)
    {
        $this->seconds = $days * 86400 + $hours * 3600 + $minutes * 60 + $seconds;
    }

    public function get_Days(): int
    {
        if ($this->seconds < 0) {
            return 0;
        }

        return (int) ($this->seconds / 86400);
    }

    public function get_Hours(): int
    {
        if ($this->seconds < 0) {
            return 0;
        }

        return (int) ($this->seconds % 86400 / 3600);
    }

    public function get_Minutes(): int
    {
        if ($this->seconds < 0) {
            return 0;
        }

        return (int) ($this->seconds % 3600 / 60);
    }

    public function get_Seconds(): int
    {
        if ($this->seconds < 0) {
            return 0;
        }

        return (int) ($this->seconds % 60);
    }

    public function get_TotalDays(): float
    {
        if ($this->seconds < 0) {
            return 0;
        }

        return $this->seconds / 86400;
    }

    public function get_TotalHours(): float
    {
        if ($this->seconds < 0) {
            return 0;
        }

        return $this->seconds / 3600;
    }

    public function get_TotalMinutes(): float
    {
        if ($this->seconds < 0) {
            return 0;
        }

        return $this->seconds / 60;
    }

    public function get_TotalSeconds(): float
    {
        if ($this->seconds < 0) {
            return 0;
        }

        return $this->seconds;
    }

    public function get_TotalMilliSeconds(): float
    {
        return $this->seconds * 1000;
    }

    public static function fromDays(float $days): TimeSpan
    {
        return new TimeSpan(0, 0, 0, $days * 86400);
    }

    public static function fromHours(float $hours): TimeSpan
    {
        return new TimeSpan(0, 0, 0, $hours * 3600);
    }

    public static function fromMinutes(float $minutes): TimeSpan
    {
        return new TimeSpan(0, 0, 0, $minutes * 60);
    }

    public static function fromSeconds(float $seconds): TimeSpan
    {
        return new TimeSpan($seconds);
    }
}
