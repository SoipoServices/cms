<?php

namespace SoipoServices\Cms\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use SoipoServices\Cms\Constants\Resources;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function home(Request $request): Application|Factory|View
    {
        $page = static::getModelClassName(Resources::PAGE)::where('is_home', true)->first();

        return view("cms::home", compact('page'));
    }

    /**
     * @return Application|Factory|View
     */
    public function about(): Application|Factory|View
    {
        return view('cms::pages.about');
    }

    /**
     * @return Application|Factory|View
     */
    public function contact(): Application|Factory|View
    {
        return view('cms::pages.contact');
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Application|Factory|View
     * @throws \Exception
     */
    public function show(Request $request, $slug): Application|Factory|View
    {
        $page = cache()->remember("cms.".$slug, now()->addMinutes(10), function () use($slug) {
            return static::getModelClassName(Resources::PAGE)::where('slug', $slug)->published()->firstOrFail();
        });

        return view('cms::pages.show',compact(['page']));
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Application|Factory|View|null
     */
    public function preview(Request $request, $slug): View|Factory|Application|null
    {
        if (auth()->user()) {
            $page = static::getModelClassName(Resources::PAGE)::where('slug', $slug)->firstOrFail();
            return view('cms::pages.show',compact('page'));
        }

        abort(403);
    }

    /**
     * @param Request $request
     * @return View|Factory|Application
     */
    public function search(Request $request): View|Factory|Application
    {
        return view('cms::pages.search');
    }
}
