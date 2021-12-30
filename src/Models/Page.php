<?php

namespace SoipoServices\Cms\Models;

use SoipoServices\Cms\Traits\MetaTagable;
use SoipoServices\Cms\Traits\Parsable;
use SoipoServices\Cms\Traits\Sluggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use SoipoServices\Cms\Traits\Publishable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Page extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasTags, HasFactory, MetaTagable, Sluggable, Publishable;

    /**
     * Fillable properties.
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'summary',
        'body',
        'scheduled_for',
        'slug'
    ];

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
        'scheduled_for' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array<string>
     */
    protected $dates = [
        'scheduled_for',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('thumb')
            ->width(config('blog.image_thumb_settings.width'))
            ->height(config('blog.image_thumb_settings.height'))
            ->sharpen(config('blog.image_thumb_settings.sharpen'));
    }

    public function registerMediaCollections() : void
    {
        $this->addMediaCollection(config('blog.image_collection'))->singleFile();
        // you can define as many collections as needed
        // $this->addMediaCollection('my-other-collection')
        //add options

    }

    public function ScopePublished(Builder $builder)
    {
        $builder->whereDate('scheduled_for', '<=', Carbon::today()->toDateString());
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
        return $this->belongsToMany(Menu::class, 'menu_page', 'page_id', 'menu_id');
    }

}
