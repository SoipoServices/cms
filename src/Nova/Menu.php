<?php

namespace SoipoServices\Cms\Nova;

use SoipoServices\Cms\Constants\Resources;
use SoipoServices\Cms\Models\Menu as AppMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Menu extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = AppMenu::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules(['required', 'string', 'max:255'])
                ->creationRules(['unique:Menus,name'])
                ->updateRules(['unique:Menus,name,{{resourceId}}']),

            Text::make(__('Slug'), 'slug')
                ->dependsOn(
                    ['name'],
                    function (Text $field, NovaRequest $request, FormData $formData) {
                        if ($formData->name != optional($request->resource()::find($request->resourceId))->name) {
                            $field->value = Str::slug($formData->name);
                            $field->help(config('app.url').'/menus/'.$field->value);
                        }
                    }
                )->rules(['required', 'string'])
                ->sortable(),

            BelongsToMany::make('Pages', 'pages', static::getNovaClassName(Resources::PAGE)),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Get the logical group associated with the resource.
     *
     * @return string
     */
    public static function group()
    {
        return __('CMS');
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Menus');
    }
}