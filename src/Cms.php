<?php

namespace SoipoServices\Cms;

class Cms
{
    /**
     * Get the currently set URI path for the blog.
     *
     * @return string
     */
    public static function path(): string
    {
        return config('press.path', 'blogs');
    }
}
