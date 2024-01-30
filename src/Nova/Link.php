<?php

namespace SoipoServices\Cms\Nova;

use SoipoServices\Cms\Constants\Resources;
use SoipoServices\Cms\Models\Page as AppPage;
use SoipoServices\Cms\Nova\Actions\PreviewAction;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\TagsField\Tags;
use SoipoServices\Cms\Models\Link as AppLink;
use SoipoServices\Cms\Traits\GetClass;

class Link extends Resource
{
    use GetClass;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = AppLink::class;

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
                ->translatable()
                ->rules(['required', 'string', 'max:255']),
            
            Text::make(__('Key to url path'), 'url_key'),
            
            Text::make(__('Slug'), 'slug')
                ->dependsOn(
                    ['name'],
                    function (Text $field, NovaRequest $request, FormData $formData) {
                        if ($formData->url_key != optional($request->resource()::find($request->resourceId))->name) {
                            $field->value = is_null($formData->url_key) ? '':Str::slug($formData->url_key);
                            $field->help(config('app.url').'/links/'.$field->value);
                        }
                    }
                )->rules(['required', 'string'])
                ->sortable(),
            Boolean::make(__('Open in a new window'), 'blank_target')
                ->help(__('Opens the linked document in a new window when selected'))->nullable(),
            Text::make(__('Href'), 'href')->rules(['required', 'string'])
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
        return [
        ];
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
        return [
            //
        ];
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
        return __('Links');
    }
}
