@foreach($pages as $page)
    <li>
        <a class="no-underline text-gray-700 hover:text-gray-900 font-semibold block pb-2 text-sm"
            href="{{ route('pages.show',['slug'=>$page->slug]) }}">{{ $page->title }}</a>
    </li>
@endforeach
