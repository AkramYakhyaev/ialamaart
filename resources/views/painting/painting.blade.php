@extends ('layout')

@section ('title', $painting->name)

@section ('content')
    
    <div class="painting">
        <input id="painting-id" type="text" value="{{ $painting->id }}" hidden>
        <div class="painting__left-column">
            @if (file_exists('storage/paintings/'.$painting->id.'/0.jpg'))
                <img class="painting__image" src="{{ asset('storage/paintings/'.$painting->id.'/0.jpg') }}">
            @else
                <img class="painting__image" src="{{ asset('storage/default.jpg') }}">
            @endif
            <div class="painting__images painting__images--mobile">
                @foreach ($images as $image)
                    @if ($loop->first)
                        <div class="painting__images-item painting__images-item--active" style="background-image: url({{ asset('storage/'.$image) }});"></div>
                    @else
                        <div class="painting__images-item" style="background-image: url({{ asset('storage/'.$image) }});"></div>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="painting__right-column">
            <h1 class="painting__name">{{ $painting->name }}</h1>
            <p class="painting__description">{{ $painting->description }}</p>
            <div class="painting__artist-and-category">
                <div class="painting__artist-and-category-row">
                    <span class="painting__artist-and-category-key">Category</span>
                    <a class="painting__artist-and-category-value" href="{{ route('painting::paintings', ['category' => $painting->category()->first()->link]) }}">{{ $painting->category()->first()->name }}</a>
                </div>
            </div>
            <div class="painting__images">
                @foreach ($images as $image)
                    @if ($loop->first)
                        <div class="painting__images-item painting__images-item--active" style="background-image: url({{ asset('storage/'.$image) }});"></div>
                    @else
                        <div class="painting__images-item" style="background-image: url({{ asset('storage/'.$image) }});"></div>
                    @endif
                @endforeach
            </div>
            <div class="painting__price-block">
                <span class="painting__price">${{ $painting->price }}</span>
                @if ($addedToCart)
                    <span class="painting__cart-button painting__cart-button--added"><i class="material-icons">remove_shopping_cart</i>Remove from cart</span>
                @else
                    <span class="painting__cart-button"><i class="material-icons">add_shopping_cart</i>Add to cart</span>
                @endif
            </div>
        </div>
    </div>

@endsection