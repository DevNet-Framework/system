<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

class TimeSpan
{
    private float $seconds = 0;

    public int $Days { get => (int) $this->seconds / 86400; }
    public int $Hours { get => (int) $this->seconds % 86400 / 3600; }
    public int $Minutes { get => (int) $this->seconds % 3600 / 60; }
    public int $Seconds { get => (int) $this->seconds % 60; }
    public float $TotalDays { get => $this->seconds / 86400; }
    public float $TotalHours { get => $this->seconds / 3600; }
    public float $TotalMinutes { get => $this->seconds / 60; }
    public float $TotalSeconds { get => $this->seconds; }
    public float $TotalMilliSeconds { get => $this->seconds * 1000; }

    public function __construct(int $days = 0, int $hours = 0, int $minutes = 0, float $seconds = 0)
    {
        $days    = $days < 0 ? 0 : $days;
        $hours   = $hours < 0 ? 0 : $hours;
        $minutes = $minutes < 0 ? 0 : $minutes;
        $seconds = $seconds < 0 ? 0 : $seconds;

        $this->seconds = $days * 86400 + $hours * 3600 + $minutes * 60 + $seconds;
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
