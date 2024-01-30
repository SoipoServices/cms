<?php

namespace SoipoServices\Cms\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use SoipoServices\Cms\Constants\Resources;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View|Application
     * @throws Exception
     */
    public function index(Request $request): View|Factory|Application
    {
        $posts = cache()->remember('cms.posts.page'.$request->get('page'), now()->addMinutes(config('cms.cache_minutes')), function () {
            return static::getModelClassName(Resources::POST)::with(Resources::CATEGORY)->published()->orderBy('scheduled_at', 'desc')->paginate(config('cms.paginate'));
        });

        $latest_posts = $posts->take(config('cms.take'));

        return view('cms::blog.index', compact(['posts', 'latest_posts']));
    }

    /**
     * @param string $slug
     * @param Request $request
     * @return View|Factory|Application
     * @throws Exception
     */
    public function single(string $slug, Request $request): View|Factory|Application
    {
        $post = cache()->remember($slug, now()->addMinutes(10), function () use ($slug) {
            return static::getModelClassName(Resources::POST)::where('slug', $slug)->published()->firstOrFail();
        });

        return view('cms::blog.single', compact(['post']));
    }

    /**
     * @param string $year
     * @param string $month
     * @param string $day
     * @param string $slug
     * @param Request $request
     * @return View|Factory|Application
     * @throws Exception
     */
    public function date(string $year,string $month,string $day,string $slug, Request $request): View|Factory|Application
    {
        $createdAt = Carbon::create($year, $month, $day);
        $post = cache()->remember($slug.$createdAt->format('-Y-m-d'), now()->addMinutes(config('cms.cache_minutes')), function () use($slug,$createdAt) {
            return static::getModelClassName(Resources::POST)::where('slug', $slug)->whereDate('created_at',$createdAt)->published()->firstOrFail();
        });

        return view('cms::blog.single', compact(['post']));
    }

    /**
     * @param string $slug
     * @param Request $request
     * @return View|Factory|Application
     * @throws Exception
     */
    public function category(string $slug, Request $request): View|Factory|Application
    {
        $posts = cache()->remember('cms.posts.category.'.$slug.'.page'.$request->get('page'), now()->addMinutes(config('cms.cache_minutes')), function () use($slug) {
            return  static::getModelClassName(Resources::POST)::published()->orderBy('scheduled_at', 'desc')->whereHas('category', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })->paginate(config('cms.paginate'));
        });

        if(count($posts)){
            $category = $posts->first()->category;
        }else{
            $category = static::getModelClassName(Resources::CATEGORY)::where('slug',$slug)->firstOrFail();
        }

        return view('cms::blog.category', compact(['category', 'posts']));
    }

    /**
     * @param Request $request
     * @return View|Factory|Application
     */
    public function search(Request $request): View|Factory|Application
    {
        $key = $request->input('query');
        $posts = static::getModelClassName(Resources::POST)::where('title','like', "%$key%")->with(Resources::CATEGORY)->published()->orderBy('scheduled_at', 'desc')->paginate(config('cms.paginate'));
        
        return view('cms::pages.search', compact(['posts', 'key']));
    }
}
