@extends ('layout')

@section ('title', $artist->name)

@section ('content')
    
    <div class="page-header">
        <h1 class="page-header__title">{{ $artist->name }}</h1>
        <p class="page-header__text">{{ $artist->description }}</p>
    </div>

    <div class="breadcrumbs"></div>

    <div class="paintings">

        <div class="paintings__categories">
            <div class="paintings__categories-block">
                <h3 class="paintings__categories-title">Categories</h3>
                <ul class="paintings__categories-list">
                    @foreach ($categories as $category)
                        <li class="paintings__category-item">
                            <a class="paintings__category-link" href="{{ route('category::category', ['link' => $category->link]) }}">{{ $category->name }}</a>
                        </li>
                    @endforeach
                    {{--
                    <li class="paintings__category-more-item">
                        <a class="paintings__category-more-link" href="{{ route('category::categories') }}">More categories</a>
                    </li>
                    --}}
                </ul>
            </div>
            {{--
            <div class="paintings__categories-block">
                <h3 class="paintings__categories-title">Artists</h3>
                <ul class="paintings__categories-list">
                    @foreach ($artists as $artist)
                        <li class="paintings__category-item">
                            <a class="paintings__category-link" href="{{ route('artist::artist', ['link' => $artist->link])}}">{{ $artist->name }}</a>
                        </li>
                    @endforeach
                    <li class="paintings__category-more-item">
                        <a class="paintings__category-more-link" href="{{ route('artist::artists') }}">More artists</a>
                    </li>
                </ul>
            </div>
            --}}
        </div>

        @include ('painting.paintingsList')

    </div>

@endsection