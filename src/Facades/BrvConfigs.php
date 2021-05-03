<?php

namespace Phobrv\BrvConfigs\Facades;

use Illuminate\Support\Facades\Facade;

class BrvConfigs extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'brvconfigs';
    }
}
