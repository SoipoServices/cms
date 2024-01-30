<?php

namespace SoipoServices\Cms\Traits;

trait GetClass
{
    protected static function getModelClassName(string $class): string
    {
        return config("cms.classes.model.$class");
    }

    protected static function getNovaClassName(string $class): string
    {
        return config("cms.classes.nova.$class");
    }
}
