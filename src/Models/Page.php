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

class Page extends Model implements HasMedia
{
    const TRANSLATABLE = ['summary', 'title', 'body'];
   
    use SoftDeletes, InteractsWithMedia, HasTags, HasFactory, Sluggable, Publishable, GetClass, HasTranslations;

    /**
     * Fillable properties.
     * @var array<string>
     */
    protected $fillable = [
        'author_id',
        'title',
        'url_key',
        'summary',
        'body',
        'scheduled_at',
        'slug',
        'is_home'
    ];
    
    public array $translatable = self::TRANSLATABLE;

    /**
     * Appended fields.
     * @var array<string>
     */
    protected $appends = ['published'];

    /**
     * The attributes that should be cast to native types.
     * @var array<string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'summary' => 'json', 
        'title' => 'json',
        'body' => 'json'
    ];
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(config('cms.image_thumb_settings.width'))
            ->height(config('cms.image_thumb_settings.height'))
            ->sharpen(config('cms.image_thumb_settings.sharpen'));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(config('cms.image_collection'))->singleFile();
        // you can define as many collections as needed
        // $this->addMediaCollection('my-other-collection')
        //add options

    }

    public function ScopePublished(Builder $builder)
    {
        $builder->whereDate('scheduled_at', '<=', Carbon::today()->toDateString());
    }

    public function ScopeOrderByFeatured(Builder $builder)
    {
        $builder->orderBy('featured', 'desc');
    }

    /**
     * @return BelongsToMany
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(static::getModelClassName(Resources::MENU), 'menu_page', 'page_id', 'menu_id');
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(static::getModelClassName(Resources::AUTHOR), 'author_id');
    }

}
