@if(isset($categories) && $categories)
    <aside class="rounded shadow overflow-hidden mb-6">
        <h3 class="text-sm bg-gray-100 text-gray-700 py-3 px-4 border-b">{{__('Categories')}}</h3>
        <div class="p-4">
            <ul class="list-reset leading-normal">
                @foreach ($categories as $category)
                    <li><a href="{{ route('blog.category',[$category->slug])}}" class="text-gray-darkest text-sm">{{$category->name}}</a></li>
                @endforeach
            </ul>
        </div>
    </aside>
@endif
