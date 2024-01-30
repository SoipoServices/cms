<?php

namespace SoipoServices\Cms\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SoipoServices\Cms\Constants\Resources;
use SoipoServices\Cms\Traits\GetClass;
use SoipoServices\Cms\Traits\Publishable;
use SoipoServices\Cms\Traits\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

class Post extends Model implements HasMedia
{
    const TRANSLATABLE = ['summary', 'title', 'body'];

    use SoftDeletes, InteractsWithMedia, HasTags, HasFactory, Sluggable, Publishable, GetClass, HasTranslations;

    /**
     * Fillable properties.
     * @var array<string>
     */
    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'url_key',
        'summary',
        'body',
        'scheduled_at',
        'featured',
        'slug',
        'featured_image'
    ];

    public array $translatable = self::TRANSLATABLE;

    /**
     * Appended fields.
     * @var array<string>
     */
    protected $appends = ['published'];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'featured' => 'boolean',
        'scheduled_at' => 'datetime',
        'summary' => 'json', 
        'title' => 'json',
        'body' => 'json'
    ];

    /**
     * Published mutator.
     * @return bool
     */
    public function getPublishedAttribute()
    {
        return now() >  $this->attributes["scheduled_at"];
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(static::getModelClassName(Resources::CATEGORY));
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(static::getModelClassName(Resources::AUTHOR), 'author_id');
    }

    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('thumb')
            ->width(config('cms.image_thumb_settings.width'))
            ->height(config('cms.image_thumb_settings.height'))
            ->sharpen(config('cms.image_thumb_settings.sharpen'));
    }

    public function registerMediaCollections() : void
    {
        $this->addMediaCollection(config('cms.image_collection'))->singleFile();
    }

    public function ScopePublished(Builder $builder)
    {
        $builder->whereDate('scheduled_at', '<=', Carbon::today()->toDateString())->with(['tags','category']);
    }

    public function ScopeOrderByFeatured(Builder $builder)
    {
        $builder->orderBy('featured', 'desc')->with(['tags','category']);
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'posts_index';
    }

    /**
     * This method is used by scout to define if is searchable or not
     */
    public function shouldBeSearchable()
    {
        return $this->published;
    }
}
