@extends ('admin.layout')

@section ('title', 'Featured artists')

@section ('panel')
    
    <a class="dashboard-header__button" href="{{ route('admin::addFeatured') }}">+ Add featured artist</a>
    <a class="dashboard-header__button dashboard-header__button--update-featured">Save changes</a>

@endsection

@section ('content')

    <form class="dashboard-featured">
        @foreach ($featured as $featuredItem)
            <div class="dashboard-featured__item">
                <span class="dashboard-featured__artist">{{ $featuredItem->artist }}</span>
                <div class="dashboard-featured__images">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="dashboard-featured__image" style="background-image: url({{ "/storage/featured/".$featuredItem->id."/".$i.".jpg" }})"></div>
                    @endfor
                </div>
                <div class="dashboard-featured__manage">
                    <div class="dashboard-featured__manage-column">
                        @if ($featuredItem->current == 1)
                            <input class="dashboard-featured__radio" type="radio" name="featured" id="{{ $featuredItem->id }}" checked>
                        @else
                            <input class="dashboard-featured__radio" type="radio" name="featured" id="{{ $featuredItem->id }}">
                        @endif
                        <label class="dashboard-featured__label" for="{{ $featuredItem->id }}">Set current</label>
                    </div>
                    <div class="dashboard-featured__manage-column">
                        <span class="dashboard-featured__remove-button">Remove</span>
                    </div>
                </div>
            </div>
        @endforeach
    </form>

@endsection