<?php

namespace SoipoServices\Cms\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SoipoServices\Cms\Traits\MetaTagable;
use SoipoServices\Cms\Traits\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasTags, HasFactory, MetaTagable, Sluggable;

    /**
     * Fillable properties.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'summary',
        'body',
        'scheduled_for',
        'featured',
        'slug'
    ];

    /**
     * Appended fields.
     * @var array
     */
    protected $appends = ['published'];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'featured' => 'boolean',
        'scheduled_for' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'scheduled_for',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Published mutator.
     * @return bool
     */
    public function getPublishedAttribute()
    {
        return now() > $this->scheduled_for;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


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
    }

    public function ScopePublished(Builder $builder)
    {
        $builder->whereDate('scheduled_for', '<=', Carbon::today()->toDateString())->with(['tags','category']);
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
