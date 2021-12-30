<?php

namespace SoipoServices\Cms\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Parsedown;

trait Parsable {

    /**
     * Get the Post's text as HTML
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function fieldAsHtml($value)
    {
        // Hash the text.
        $key = 'post|'.$this->id.'|'.Hash::make($value);
        // If the cache with this hash exists, return it, otherwise
        // parse it again and save it into the cache for 1 day.
        return Cache::remember($key, 86400, function () use ($value) {
            return $this->parseMarkdownToHtml($value);
        });
    }

    /**
     * Get the Article's text as HTML
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function parseMarkdownToHtml(string $text)
    {

        return new HtmlString(parsedown()->setBreaksEnabled(true)->text($text));
    }
}
