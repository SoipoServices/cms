<?php

namespace SoipoServices\Cms\View\Components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use SoipoServices\Cms\Constants\Resources;
use SoipoServices\Cms\Traits\GetClass;

class LatestPosts extends Component
{
    use GetClass;

    public function __construct()
    {
        //
    }

    /**
     * @return View|Factory|Htmlable|\Closure|string|Application
     * @throws \Exception
     */
    public function render(): View|Factory|Htmlable|\Closure|string|Application
    {
        $posts = cache()->remember('cms.latest_posts', now()->addMinutes(10), function () {
            return static::getModelClassName(Resources::POST)::published()->orderByFeatured()->limit(5)->get();
        });

        return view('components.latest-posts', compact('posts'));
    }
}
