<?php

namespace SoipoServices\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MetaTag extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * Fillable properties.
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'path',
        'keywords'
    ];
}
