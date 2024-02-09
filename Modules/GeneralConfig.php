<?php

namespace Modules;

class GeneralConfig
{
    public static function getGeneralConfigFileName(): string
    {
        return 'general-module-config';
    }

    public static function stopOnFirstFailure(): bool
    {
        return (bool) config(static::getGeneralConfigFileName().'.requests.stop_on_first_failure', false);
    }

    public static function setStopOnFirstFailure($value = false): void
    {
        config([static::getGeneralConfigFileName().'.requests.stop_on_first_failure' => $value]);
    }
}
