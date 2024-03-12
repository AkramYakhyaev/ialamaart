<div class="paintings__list">
    @foreach ($paintings as $painting)
        <a class="painting" href="{{ route('painting::painting', ['link' => $painting->link]) }}">
            <div class="painting__wrap-preview">
                @if (file_exists('storage/paintings/'.$painting->id.'/0.jpg'))
                    <div class="painting__preview" style="background-image: url({{ asset('/storage/paintings/'.$painting->id.'/0.jpg') }});"></div>
                @else
                    <div class="painting__preview" style="background-image: url({{ asset('/storage/default.jpg') }});"></div>
                @endif
                <div class="painting__overlay">
                    <span class="painting__price">${{ $painting->price }}</span>
                    <object class="painting__category"><a href="{{ route('painting::paintings', ['category' => $painting->category()->first()->link]) }}">{{ $painting->category()->first()->name }}</a></object>
                </div>
            </div>
            <span class="painting__name">{{ $painting->name }}</span>
        </a>
    @endforeach
    @if ($needPager)
        @include ('painting.pager', ['page' => $page, 'total' => $total])
    @endif
</div>