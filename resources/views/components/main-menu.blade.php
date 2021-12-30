@foreach($pages as $page)
    <li class="flex items-center">
        <a class="no-underline lg:text-white lg:hover:text-gray-300 text-gray-800 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
            href="{{ route('pages.show',['slug'=>$page->slug]) }}"><i
                class="lg:text-gray-300 text-gray-500 far fa-file-alt text-lg leading-lg mr-2"></i>{{ $page->title }}</a>
    </li>
@endforeach
