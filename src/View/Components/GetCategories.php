<?php

namespace SoipoServices\Cms\View\Components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use SoipoServices\Cms\Constants\Resources;
use SoipoServices\Cms\Traits\GetClass;

class GetCategories extends Component
{
    use GetClass;

    public function __construct()
    {
        //
    }

    /**
     * @throws \Exception
     */
    public function render(): View|Factory|Htmlable|\Closure|string|Application
    {
        $categories = cache()->remember('cms.categories', now()->addMinutes(10), function () {
            return static::getModelClassName(Resources::CATEGORY)::limit(5)->get();
        });

        return view('components.get-categories', compact('categories'));
    }
}
