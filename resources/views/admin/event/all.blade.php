@extends ('admin.layout')

@section ('title', 'Events')

@section ('panel')
    
    <a class="dashboard-header__button" href="{{ route('admin::addEvent') }}">+ Add event</a>

@endsection

@section ('content')

    <div class="dashboard-news">
        @foreach ($news as $newsItem)
            <a class="dashboard-news__item" href="{{ route('admin::editEvent', ['id' => $newsItem->id]) }}">
                <img class="dashboard-news__icon" src="{{ asset('storage/logo.png') }}">
                <div class="dashboard-news__content">
                    <span class="dashboard-news__text">{{ $newsItem->text }}</span>
                    <span class="dashboard-news__date">{{ Carbon\Carbon::parse($newsItem->created_at)->format('Y F, jS') }}</span>
                </div>
            </a>
        @endforeach
    </div>

@endsection