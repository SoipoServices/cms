<?php

namespace SoipoServices\Cms\Traits;

use Illuminate\Support\Carbon;

trait Publishable
{

    /**
     * Published mutator.
     * @return bool
     */
    public function getPublishedAttribute()
    {
        return Carbon::now() > $this->attributes["scheduled_at"];
    }
}
