<?php

namespace SoipoServices\Cms\Http\Controllers;

use SoipoServices\Cms\Models\Page;
use Illuminate\Http\Request;


class PageController extends Controller
{
    public function home(Request $request)
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function show(Request $request, $slug)
    {
        $page = cache()->remember("cms.".$slug, now()->addMinutes(10), function () use($slug) {
            return Page::where('slug', $slug)->published()->firstOrFail();
        });

        $metaTags = $page->metaTags;

        return view('pages.show',compact(['page','metaTags']));
    }

    public function preview(Request $request, $slug)
    {
        if (auth()->user()) {
            $page =  Page::where('slug', $slug)->firstOrFail();
            return view('pages.show',compact('page'));
        }
        abort(403);
    }

    public function search(Request $request)
    {
        return view('pages.search');
    }

}
