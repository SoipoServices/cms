<?php

namespace SoipoServices\Cms\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Sluggable
{


    /**
     * @var array
     */
    protected static $sluggableEvents = [
        'saving',
        'updating',
    ];

    /**
     * Leverage bootableTraits functionality in case I need to stack multiple.
     */
    public static function bootSluggable(): void
    {
        foreach (static::$sluggableEvents as $event) {
            static::{$event}(function ($model) use ($event) {
                $column = static::getSluggableField($model);
                $model->attributes['slug'] = static::generateUniqueSlug(
                    $model->{$column},
                    $event === 'updating'
                        ? $model->id
                        : null
                );
            });
        }
    }

    /**
     * Fetch sluggable field.
     * @param Model $model
     * @return string|null
     */
    protected static function getSluggableField(Model $model): ?string
    {
        return 'title';
    }

    /**
     * Generate a unique slug.
     * @param string $fieldValue
     * @param int|null $oldId
     * @return string
     */
    protected static function generateUniqueSlug(string $fieldValue, int $oldId = null): string
    {
        $slug = Str::slug($fieldValue);

        $additionalQuery = static::getAdditionalQueryString($oldId);

        $latestSlug = static::whereRaw("(slug = '$slug' or slug LIKE '$slug-%'){$additionalQuery}")
            ->latest('id')
            ->value('slug');

        if ($latestSlug) {
            $pieces = explode('-', $latestSlug);

            $slug .= '-' . (intval(end($pieces)) + 1);
        }

        return $slug;
    }

    /**
     * Additional check for "updating" event.
     * Solves a problem when updating without changing sluggable field.
     * @param int|null $oldId
     * @return string
     */
    protected static function getAdditionalQueryString($oldId): string
    {
        if (is_null($oldId)) {
            return '';
        }

        return " AND id != '$oldId'";
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
