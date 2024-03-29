<?php

namespace SoipoServices\Cms\Models;

use SoipoServices\Cms\Constants\Resources;
use SoipoServices\Cms\Traits\GetClass;
use SoipoServices\Cms\Traits\MetaTagable;
use SoipoServices\Cms\Traits\Parsable;
use SoipoServices\Cms\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    const TRANSLATABLE = ['name', 'description'];

    use Sluggable, Parsable, HasFactory, GetClass, HasTranslations;

    /**
     * Model fillable fields.
     * @var array<string>
     */
    protected $fillable = [
        'url_key',
        'name',
        'description',
    ];
    public array $translatable = self::TRANSLATABLE;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'json',
        'url_key' => 'string',
        'description' => 'json'
    ];



    public static function boot()
    {
        parent::boot();
    }

    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(static::getModelClassName(Resources::POST));
    }

    protected static function getSluggableField(Model $model): ?string
    {
        return 'name';
    }


    public function descriptionAsHtml()
    {
        $value = $this->attributes['description'];
        if ($value) {
            return $this->fieldAsHtml($value);
        }
    }
}
