<?php

namespace Co2Control\Enumerables;

class SensorStatus extends Enum
{
    const OK = 'OK';
    const WARN = 'WARN';

    public static function ok(): self
    {
        return self::fromString(self::OK);
    }
}
