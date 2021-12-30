<?php

namespace SoipoServices\Cms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SoipoServices\Cms\Cms
 */
class Cms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cms';
    }
}
