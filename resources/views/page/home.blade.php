@extends ('layout')

@section ('title', __('title.home'))

@section ('content')

    <div class="home">
        <div class="home__left-column">
            <div class="home__text-block">
                <p class="home__text">Welcome! Are you looking for a unique gift for someone or yourself?</p>
                <p class="home__text">Then you are at right place! Ialama Art Gallery & Shop offers digital art packages, antique oil paintings and other art services. We have art for you, your family or your friends.</p>
                <p class="home__text">Our goal is to help you find the right art that can bring your friends beautiful smile as digital art packages gift, unique oil paintings for the art collection that you or your friends run or maybe you want to have something new as a profile picture. We can help you to satisfy your desires!</p>
                <p class="home__text">Fell free to check our work and collection at Facebook, Instagram and Pinterest. You can reach us by calling <a href="tel:312-877-7788">(312) 877-7788</a> or by emailing <a href="mailto:info@ialamaart.com">info@ialamaart.com</a>.</p>
            </div>
            <div class="home__events">
                <h2 class="home__events-header">Events</h2>
                <div class="home__events-items">
                    @foreach ($news as $newsItem)
                        <div class="home__events-item">
                            <img class="home__events-image" src="{{ asset('storage/logo.png') }}">
                            <div class="home__events-date-block">
                                <span class="home__events-date-first">{{ Carbon\Carbon::parse($newsItem->created_at)->format('F, jS') }}</span>
                                <span class="home__events-date-second">{{ Carbon\Carbon::parse($newsItem->created_at)->format('Y') }}</span>
                            </div>
                            <span class="home__events-text">{{ $newsItem->text }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="home__right-column">
            <div class="home__gallery">
                <div class="home__gallery-current" style="background-image: url({{ "/storage/featured/".$featuredArtist->id."/1.jpg" }});"></div>
                <div class="home__gallery-items">
                    @for ($i = 1; $i <= 4; $i++)
                        @if ($i == 1)
                            <div class="home__gallery-item home__gallery-item--selected" style="background-image: url({{ "/storage/featured/".$featuredArtist->id."/".$i.".jpg" }})"></div>
                        @else
                            <div class="home__gallery-item" style="background-image: url({{ "/storage/featured/".$featuredArtist->id."/".$i.".jpg" }})"></div>
                        @endif
                    @endfor
                </div>
                <span class="home__gallery-artist">Featured artist:<span>{{ $featuredArtist->artist }}</span></span>
            </div>
        </div>
    </div>

@endsection