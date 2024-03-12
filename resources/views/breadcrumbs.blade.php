<div class="wrap-breadcrumbs">
    <div class="breadcrumbs">
        @for ($i = 0; $i < count($breadcrumbs); $i++)

            @if ($breadcrumbs[$i]['href'])
                <a class="breadcrumbs__item breadcrumbs__item--link" href="{{ $breadcrumbs[$i]['href'] }}">{{ $breadcrumbs[$i]['name'] }}</a>
            @else
                <span class="breadcrumbs__item">{{ $breadcrumbs[$i]['name'] }}</span>
            @endif

            @if ($i < count($breadcrumbs) - 1)
                <span class="breadcrumbs__item--delimiter">➞</span>
            @endif
            
        @endfor
    </div>
</div>