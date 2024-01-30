<?php

namespace SoipoServices\Cms\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SoipoServices\Cms\Constants\Resources;
use SoipoServices\Cms\Traits\GetClass;
use SoipoServices\Cms\Traits\Sluggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SoipoServices\Cms\Traits\Publishable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

class Link extends Model
{
    const TRANSLATABLE = ['name'];

    use SoftDeletes, HasTags, HasFactory, Publishable, GetClass, HasTranslations;

    /**
     * Fillable properties.
     * @var array<string>
     */
    protected $fillable = [
        'name', 'slug', 'href', 'blank_target', 'url_key'
    ];

    public array $translatable = self::TRANSLATABLE;

     /**
     * The attributes that should be cast to native types.
     * @var array<string>
     */
    protected $casts = [
        'url_key' => 'string',
        'name' => 'json'
    ];

    /**
     * @return BelongsToMany
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(static::getModelClassName(Resources::MENU), 'menu_page', 'link_id', 'menu_id');
    }
}
