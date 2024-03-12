<div class="dashboard-header__breadcrumbs">
    <a class="dashboard-header__home" href="{{ route('page::home') }}"><i class="material-icons">home</i></a>
    @for ($i = 0; $i < count($breadcrumbs); $i++)

        @if ($breadcrumbs[$i]['href'])
            <a class="dashboard-header__breadcrumb dashboard-header__breadcrumb--link" href="{{ $breadcrumbs[$i]['href'] }}">{{ $breadcrumbs[$i]['name'] }}</a>
        @else
            <span class="dashboard-header__breadcrumb">{{ $breadcrumbs[$i]['name'] }}</span>
        @endif

        @if ($i < count($breadcrumbs) - 1)
            <span class="dashboard-header__breadcrumb--delimiter">➞</span>
        @endif
        
    @endfor
</div>