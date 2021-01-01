<?php

namespace Co2Control\Enumerables;

class SensorStatus extends Enum
{
    const OK = 'OK';
    const WARN = 'WARN';
    const ALERT = 'ALERT';

    public static function ok(): self
    {
        return self::fromString(self::OK);
    }

    public static function warning(): self
    {
        return self::fromString(self::WARN);
    }

    public static function alert(): self
    {
        return self::fromString(self::ALERT);
    }

    public function isOk(): bool
    {
        return self::is(self::OK);
    }

    public function isWarning(): bool
    {
        return self::is(self::WARN);
    }

    public function isAlert(): bool
    {
        return self::is(self::ALERT);
    }
}
