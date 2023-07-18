@if($pageCount  && $pageCount > 1)

    <nav aria-label="...">
        <ul class="pagination">
            @if(isset($previous))
            <li class="page-item">
                <a class="page-link" href="{{ $route }}?{{ http_build_query(array_merge($_GET,array('page' => $previous))) }}" tabindex="-1">
                    &laquo;
                </a>
            </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">
                        &laquo;
                    </a>
                </li>
            @endif

            @foreach($pagesInRange as $page)
                    @if($page != $current)
            <li class="page-item"><a class="page-link" href="{{ $route }}?{{ http_build_query(array_merge($_GET,array('page' => $page))) }}">{{ $page }}</a></li>
                    @else
            <li class="page-item active">
                <a class="page-link" href="#">{{ $page }} <span class="sr-only">({{ __lang('current') }})</span></a>
            </li>
                    @endif
            @endforeach

                @if(isset($next))

            <li class="page-item">
                <a class="page-link" href="{{ $route }}?{{ http_build_query(array_merge($_GET,array('page' => $next))) }}">&raquo;</a>
            </li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link" href="#">&raquo;</a>
                    </li>
                @endif
        </ul>
    </nav>

@endif

