<?php

use SoipoServices\Cms\Models\Category;
use SoipoServices\Cms\Models\Menu;
use SoipoServices\Cms\Models\Page;
use SoipoServices\Cms\Models\Post;

// config for SoipoServices/Cms
return [

    /*
     |--------------------------------------------------------------------------
     | Classes
     |--------------------------------------------------------------------------
     |
     | Class values that determines what model or resource to be used
     |
     */
    'classes' => [
        'model' => [
            'page' => Page::class,
            'post' => Post::class,
            'menu' => Menu::class,
            'category' => Category::class,
            'author' => 'App\Models\User'
        ],
        'nova' => [
            'page' => \SoipoServices\Cms\Nova\Page::class,
            'post' => \SoipoServices\Cms\Nova\Post::class,
            'menu' => \SoipoServices\Cms\Nova\Menu::class,
            'category' => \SoipoServices\Cms\Nova\Category::class,
            'author' => 'App\Nova\User',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | URI Address Path
    |--------------------------------------------------------------------------
    |
    | Use this path value to determine on what URI we are going to serve the
    | blog. For example, if you wanted to serve it at a different prefix
    | like www.example.com/my-blog, change the value to '/my-blog'.
    |
    */

    'path' => 'blogs',

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Use this cache minutes value to determine on how many minutes data should be
    | cached.
    */
    'cache_minutes' => 10,

    /*
    |--------------------------------------------------------------------------
    | Paginate
    |--------------------------------------------------------------------------
    |
    | Use this paginate value to determine on how many record should be returned
    | at once
    */
    'paginate' => 10,

    /*
    |--------------------------------------------------------------------------
    | Take
    |--------------------------------------------------------------------------
    |
    | Use this take value to determine on how many record should be selected
    | in a record collection
    */
    'take' => 5,

    'image_collection' => 'main',
    'image_thumb_settings' => [
        'width' => env('BLOG_THUMB_WIDTH', '350'),
        'height' => env('BLOG_THUMB_HEIGHT', '350'),
        'sharpen' => env('BLOG_THUMB_SHARPEN', '10'),
    ],
];
