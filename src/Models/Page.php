<?php

namespace SoipoServices\Models;

use App\Traits\MetaTagable;
use App\Traits\Parsable;
use App\Traits\Sluggable;
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
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Page extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasTags, HasFactory, MetaTagable;

    /**
     * Fillable properties.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'summary',
        'body',
        'scheduled_for',
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
