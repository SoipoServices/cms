<?php

namespace SoipoServices\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SoipoServices\Cms\Constants\Resources;
use SoipoServices\Cms\Traits\GetClass;

class Menu extends Model
{
    use HasFactory, GetClass;

    /**
     * Fillable properties.
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug'
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array<string>
     */
    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array<string>
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsToMany
     */
    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(static::getModelClassName(Resources::PAGE), 'menu_page', 'menu_id', 'page_id');
    }
    
    public function links(): BelongsToMany
    {
        return $this->belongsToMany(static::getModelClassName(Resources::LINK), 'menu_link', 'menu_id', 'link_id');
    }

     /**
     * TaggedCount mutator.
     * @return int
     */
    public function getPagesCountAttribute()
    {
        return $this->pages->count();
    }
}
