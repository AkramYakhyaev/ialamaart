@extends ('layout')

@section ('title', __('title.paintings'))

@section ('content')

    <div class="paintings">
        @include ('painting.paintingsList')
        <div class="filters-mobile-bar">
            <span class="filters-mobile-bar__text">Filters</span>
            <span class="filters-mobile-bar__icon-block">
                <span class="filters-mobile-bar__icon"></span>
            </span>
        </div>
        <div class="filters">
            {{-- category --}}
            <div class="filters__row">
                <span class="filters__filter-label">Category</span>
                <select class="filters__select filters__categories">
                    @if ($category)
                        <option value="all">All</option>
                        @foreach ($categories as $categoryItem)
                            @if ($category == $categoryItem->link)
                                <option value="{{ $categoryItem->link }}" selected>{{ $categoryItem->name }}</option>
                            @else
                                <option value="{{ $categoryItem->link }}">{{ $categoryItem->name }}</option>
                            @endif
                        @endforeach
                    @else
                        <option value="all" selected>All</option>
                        @foreach ($categories as $categoryItem)
                            <option value="{{ $categoryItem->link }}">{{ $categoryItem->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            {{-- price --}}
            <div class="filters__row">
                <span class="filters__filter-label">Price</span>
                <select class="filters__select filters__prices">
                    @if ($price)
                        <option value="all">All</option>
                        @foreach ($prices as $key => $value)
                            @if ($price == $key)
                                <option value="{{ $key }}" selected>{{ $value }}</option>
                            @else
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    @else
                        <option value="all" selected>All</option>
                        @foreach ($prices as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            {{-- size --}}
            <div class="filters__row">
                <span class="filters__filter-label">Size</span>
                <div class="filters__size">
                    @foreach ($sizes as $key => $value)
                        @if ($size)
                            @if ($size == $key)
                                <div class="filters__size-item filters__size-item--active" title="{{ $value['title'] }}" value="{{ $key }}">
                                    <div class="filters__size-block filters__size-block--{{ $key }}">
                                        <span class="filters__size-label">{{ $key }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="filters__size-item" title="{{ $value['title'] }}" value="{{ $key }}">
                                    <div class="filters__size-block filters__size-block--{{ $key }}">
                                        <span class="filters__size-label">{{ $key }}</span>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="filters__size-item" title="{{ $value['title'] }}" value="{{ $key }}">
                                <div class="filters__size-block filters__size-block--{{ $key }}">
                                    <span class="filters__size-label">{{ $key }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            {{-- orientation --}}
            <div class="filters__row">
                <span class="filters__filter-label">Orientation</span>
                <div class="filters__orientations">
                    @foreach ($orientations as $key => $value)
                        @if ($orientation)
                            @if ($orientation == $key)
                                <div class="filters__orientation-item filters__orientation-item--active" title="{{ $value['title'] }}" value="{{ $key }}">
                                    <span class="filters__orientation-block filters__orientation-block--{{ $key }}"></span>
                                </div>
                            @else
                                <div class="filters__orientation-item" title="{{ $value['title'] }}" value="{{ $key }}">
                                    <span class="filters__orientation-block filters__orientation-block--{{ $key }}"></span>
                                </div>
                            @endif
                        @else
                            <div class="filters__orientation-item" title="{{ $value['title'] }}" value="{{ $key }}">
                                <span class="filters__orientation-block filters__orientation-block--{{ $key }}"></span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @if (!empty($_SERVER['QUERY_STRING']))
                <a class="filters__search-button" href="?{{ $_SERVER['QUERY_STRING'] }}">Search</a>
            @else
                <a class="filters__search-button" href="">Search</a>
            @endif
        </div>
    </div>

@endsection