<?php

namespace SoipoServices\Cms\Nova;

use SoipoServices\Cms\Constants\Resources;
use SoipoServices\Cms\Models\Page as AppPage;
use SoipoServices\Cms\Nova\Actions\PreviewAction;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\TagsField\Tags;

class Page extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = AppPage::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title',
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

            Files::make('Featured image', 'featured')->enableExistingMedia(),

            Text::make(__('Title'), 'title')
                ->sortable()
                ->rules(['required']),

            Boolean::make(__('Is Home'), 'is_home'),

            Text::make(__('Slug'), 'slug')
                ->dependsOn(
                    ['title'],
                    function (Text $field, NovaRequest $request, FormData $formData) {
                        if ($formData->title != optional($request->resource()::find($request->resourceId))->title) {
                            $field->value = Str::slug($formData->title);
                            $field->help(route('pages.preview', ['slug' => $field->value]));
                        }
                    }
                )->rules(['required', 'string'])
                ->sortable(),

            BelongsTo::make(__('Author'), 'author', static::getNovaClassName(Resources::AUTHOR)),

            Textarea::make(__('Summary'), 'summary')->hideFromIndex(),

            Trix::make(__('Body'), 'body')->withFiles('media')->rules(['required', 'string']),

            DateTime::make(__('Scheduled For'), 'scheduled_at')->rules('nullable'),

            Boolean::make(__('Published'), function () {
                return $this->published;
            })->exceptOnForms(),

            Tags::make(__('Tags'), 'tags')->withLinkToTagResource(),
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
            (new PreviewAction())->onlyOnTableRow()
            ->confirmText(__('Are you sure you want to preview this page?'))
            ->confirmButtonText(__('Preview'))
            ->cancelButtonText(__('Go back')),
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
        return __('Pages');
    }
}
