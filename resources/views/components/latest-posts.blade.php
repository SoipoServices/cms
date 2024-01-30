@if(isset($latest_posts) && $latest_posts)
<aside class="rounded shadow overflow-hidden mb-6">
    <h3 class="text-sm bg-gray-100 text-gray-700 py-3 px-4 border-b">{{__('Latest Posts')}}</h3>

    <div class="p-4">
        <ul class="list-reset leading-normal">
            @foreach ($latest_posts as $latest_post)
            <li><a href="{{ route('blog.single',[$latest_post->slug])}}" class="text-gray-darkest text-sm">{{$latest_post->title}}</a></li>
            @endforeach
        </ul>
    </div>
</aside>
@endif
