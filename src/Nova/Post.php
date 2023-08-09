<?php

namespace SoipoServices\Cms\Nova;

use SoipoServices\Cms\Constants\Resources;
use SoipoServices\Cms\Models\Post as AppPost;
use SoipoServices\Cms\Nova\Metrics\PostsTrend;
use Laravel\Nova\Fields\Image;
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
use SoipoServices\Cms\Traits\GetClass;
use Spatie\TagsField\Tags;

class Post extends Resource
{
    use GetClass;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = AppPost::class;

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

            Image::make('Featured image', 'featured')->enableExistingMedia(),

            Text::make(__('Title'), 'title')
                ->sortable()
                ->rules(['required']),

            Text::make(__('Slug'), 'slug')
                ->dependsOn(
                    ['title'],
                    function (Text $field, NovaRequest $request, FormData $formData) {
                        if ($formData->title != optional($request->resource()::find($request->resourceId))->title) {
                            $field->value = Str::slug($formData->title);
                            $field->help(route('blog.single', ['slug' => $field->value]));
                        }
                    }
                )->rules(['required', 'string'])
                ->sortable(),

            Textarea::make(__('Summary'), 'summary')->hideFromIndex(),

            Trix::make(__('Body'), 'body')->withFiles('media')->rules(['required', 'string']),

            Boolean::make(__('Featured'), 'featured')->sortable(),

            BelongsTo::make(__('Category'), 'category', static::getNovaClassName(Resources::CATEGORY))
                ->nullable()
                ->sortable(),

            BelongsTo::make(__('Author'), 'author', static::getNovaClassName(Resources::AUTHOR)),

            DateTime::make(__('Scheduled For'), 'scheduled_at')
                ->rules('nullable'),

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
            (new PostsTrend())->width('1/2'),
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
        return __('Posts');
    }
}
