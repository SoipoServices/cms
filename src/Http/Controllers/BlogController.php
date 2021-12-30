<?php

namespace SoipoServices\Cms\Http\Controllers;

use SoipoServices\Cms\Models\Category;
use SoipoServices\Cms\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    private function getCategories(){
        $categories = cache()->remember('cms.categories', now()->addMinutes(10), function () {
            return Category::limit(5)->get();
        });
        return $categories;
    }

    private function getLatestPosts(){
        $latest_posts = cache()->remember('cms.latest_posts', now()->addMinutes(10), function () {
            return  Post::published()->orderByFeatured()->limit(5)->get();
        });
        return $latest_posts;
    }

    public function index(Request $request)
    {
        $posts = cache()->remember('cms.posts.page'.$request->get('page'), now()->addMinutes(10), function () {
            return  Post::published()->orderByFeatured()->paginate(10);
        });

        $latest_posts = $posts->take(5);

        $categories = $this->getCategories();

        return view('blog.index', compact(['posts', 'latest_posts', 'categories']));
    }

    public function single(string $slug, Request $request)
    {
        $latest_posts =  $this->getLatestPosts();
        $categories = $this->getCategories();

        $post = cache()->remember($slug, now()->addMinutes(10), function () use ($slug) {
            return Post::where('slug', $slug)->published()->firstOrFail();
        });

        $metaTags = $post->metaTags;

        return view('blog.single', compact(['post', 'latest_posts', 'categories', 'metaTags']));
    }

    public function date(string $year,string $month,string $day,string $slug, Request $request)
    {
        $latest_posts =  $this->getLatestPosts();
        $categories = $this->getCategories();
        $createdAt = Carbon::create($year, $month, $day);
        $post = cache()->remember($slug.$createdAt->format('-Y-m-d'), now()->addMinutes(10), function () use($slug,$createdAt) {
            return Post::where('slug', $slug)->whereDate('created_at',$createdAt)->published()->firstOrFail();
        });

        return view('blog.single', compact(['post', 'latest_posts', 'categories']));
    }

    public function category(string $slug, Request $request)
    {

        $posts = cache()->remember('cms.posts.category.'.$slug.'.page'.$request->get('page'), now()->addMinutes(10), function () use($slug) {
            return  Post::published()->orderByFeatured()->whereHas('category', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })->paginate(10);
        });

        $latest_posts =  $this->getLatestPosts();
        $categories = $this->getCategories();

        if(count($posts)){
            $category = $posts->first()->category;
        }else{
            $category = Category::where('slug',$slug)->firstOrFail();
        }

        return view('blog.category', compact(['category', 'posts', 'latest_posts', 'categories']));
    }
}
