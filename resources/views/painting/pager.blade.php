@php
    $pp = $page - 1;
    $np = $page + 1;
    $pages = ceil($total / $paintingsPerPage);
    $queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : false;
    if ($queryString) {
        parse_str($_SERVER['QUERY_STRING'], $output);
        foreach ($output as $key => $value) {
            if ($key === 'page') {
                unset($output[$key]);
            } else {
                $params[] = $key.'='.$value;
            }
        }
    } else {
        $params = "";
    }
    if (!empty($params)) {
        $urlWithoutPage = explode('?', $_SERVER['REQUEST_URI'])[0].'?'.implode('&', $params);
        $symbol = '&';
    } else {
        $urlWithoutPage = explode('?', $_SERVER['REQUEST_URI'])[0];
        $symbol = '?';
    }
    $previousUrl = $page > 2 ? $urlWithoutPage.$symbol.'page='.$pp : $urlWithoutPage;
    $nextUrl = $urlWithoutPage.$symbol.'page='.$np;
@endphp

<div class="filter-pager">
    @if ($page == 1)
        <a class="filter-pager__navigation filter-pager__navigation--previous filter-pager__navigation--inactive">←</a>
    @else
        <a class="filter-pager__navigation filter-pager__navigation--previous" href="{{ $previousUrl }}">←</a>
    @endif
    <div class="filter-pager__pages">
        @if ($page == 1)
            <span class="filter-pager__more filter-pager__more--left filter-pager__more--hidden">...</span>
            @for ($i = 1; $i < 10 and $i <= $pages; $i++)
                @if ($i == 1)
                    <span class="filter-pager__link filter-pager__link--current"><span>{{ $i }}</span></span>
                @else
                    <a class="filter-pager__link" href="{{ $urlWithoutPage.$symbol.'page='.$i }}"><span>{{ $i }}</span></a>
                @endif
            @endfor
            @if ($pages > 9)
                <span class="filter-pager__more filter-pager__more--right">...</span>
            @else
                <span class="filter-pager__more filter-pager__more--right filter-pager__more--hidden">...</span>
            @endif
        @elseif ($pages < 10)
            <span class="filter-pager__more filter-pager__more--left filter-pager__more--hidden">...</span>
            @for ($i = 1; $i <= $pages; $i++)
                @if ($i == 1)
                    <a class="filter-pager__link" href="{{ $urlWithoutPage }}"><span>{{ $i }}</span></a>
                @elseif ($i == $page)
                    <span class="filter-pager__link filter-pager__link--current"><span>{{ $i }}</span></span>
                @else
                    <a class="filter-pager__link" href="{{ $urlWithoutPage.$symbol.'page='.$i }}"><span>{{ $i }}</span></a>
                @endif
            @endfor
            <span class="filter-pager__more filter-pager__more--right filter-pager__more--hidden">...</span>
        @elseif ($page - 3 <= 2)
            <span class="filter-pager__more filter-pager__more--left filter-pager__more--hidden">...</span>
            @for ($i = 1; $i <= 9; $i++)
                @if ($i == 1)
                    <a class="filter-pager__link" href="{{ $urlWithoutPage }}"><span>{{ $i }}</span></a>
                @elseif ($i == $page)
                    <span class="filter-pager__link filter-pager__link--current"><span>{{ $i }}</span></span>
                @else
                    <a class="filter-pager__link" href="{{ $urlWithoutPage.$symbol.'page='.$i }}"><span>{{ $i }}</span></a>
                @endif
            @endfor
            <span class="filter-pager__more filter-pager__more--right">...</span>
        @elseif ($page + 3 >= $pages - 1)
            <span class="filter-pager__more filter-pager__more--left">...</span>
            @for ($i = $pages - 8; $i <= $pages; $i++)
                @if ($i == $page)
                    <span class="filter-pager__link filter-pager__link--current"><span>{{ $i }}</span></span>
                @else
                    <a class="filter-pager__link" href="{{ $urlWithoutPage.$symbol.'page='.$i }}"><span>{{ $i }}</span></a>
                @endif
            @endfor
            <span class="filter-pager__more filter-pager__more--right filter-pager__more--hidden">...</span>
        @else
            <span class="filter-pager__more filter-pager__more--left">...</span>
            @for ($i = $page - 4; $i <= $page + 4; $i++)
                @if ($i == $page)
                    <span class="filter-pager__link filter-pager__link--current"><span>{{ $i }}</span></span>
                @else
                    <a class="filter-pager__link" href="{{ $urlWithoutPage.$symbol.'page='.$i }}"><span>{{ $i }}</span></a>
                @endif
            @endfor
            <span class="filter-pager__more filter-pager__more--right">...</span>
        @endif
    </div>
    @if ($page == $pages)
        <a class="filter-pager__navigation filter-pager__navigation--next filter-pager__navigation--inactive">→</a>
    @else
        <a class="filter-pager__navigation filter-pager__navigation--next" href="{{ $nextUrl }}">→</a>
    @endif
</div>