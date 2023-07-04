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

class Category extends Model
{
    use Sluggable, Parsable, HasFactory, GetClass;

    /**
     * Model fillable fields.
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

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
