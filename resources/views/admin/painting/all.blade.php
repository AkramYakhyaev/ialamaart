@extends ('admin.layout')

@section ('title', 'Paintings')

@section ('panel')
    
    <a class="dashboard-header__button" href="{{ route('admin::addPainting') }}">+ Add painting</a>

@endsection

@section ('content')

    <div class="dashboard-paintings">
        @foreach ($paintings as $painting)
            @if ($painting->availability === 0)
                <a class="dashboard-paintings__item dashboard-paintings__item--unavailable" href="{{ route('admin::editPainting', ['link' => $painting->link]) }}">
            @elseif ($painting->availability === 1)
                <a class="dashboard-paintings__item" href="{{ route('admin::editPainting', ['link' => $painting->link]) }}">
            @endif
                <div class="dashboard-paintings__wrap-preview">
                @if (file_exists('storage/paintings/'.$painting->id.'/0.jpg'))
                    <div class="dashboard-paintings__preview" style="background-image: url({{ asset('/storage/paintings/'.$painting->id.'/0.jpg') }});"></div>
                @else
                    <div class="dashboard-paintings__preview" style="background-image: url({{ asset('/storage/default.jpg') }});"></div>
                @endif
                <div class="dashboard-paintings__overlay">
                    <span class="dashboard-paintings__price">${{ $painting->price }}</span>
                    <span class="dashboard-paintings__category">{{ $painting->category()->first()->name }}</span>
                </div>
            </div>
            <span class="dashboard-paintings__name">{{ $painting->name }}</span>
            </a>
        @endforeach
    </div>

@endsection