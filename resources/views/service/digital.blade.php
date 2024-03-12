@extends ('layoutWithoutFooter')

@section ('title', __('title.digital'))

@section ('content')
    
    <div class="digital-art-constructor">

        {{-- peoples count --}}
        <div class="digital-art-constructor__step">
            <div class="digital-art-constructor__step-header">
                <div class="digital-art-constructor__step-number"><span>1</span></div>
                <div class="digital-art-constructor__step-text">
                    <span class="digital-art-constructor__step-text-header">How many people you wish to be in order?</span>
                    <span class="digital-art-constructor__step-text-hint">If you have more then 4+ family members please <a href="{{ route('page::contact') }}">contact us</a>.</span>
                </div>
            </div>
            <div class="digital-art-constructor__categories">
                <div class="digital-art-constructor__category digital-art-constructor__category--selected" id="0">
                    <div class="digital-art-constructor__category-image" style="background-image: url({{ asset('/storage/self.jpg') }} );"></div>
                    <span class="digital-art-constructor__category-name">Self portrait packages</span>
                </div>
                <div class="digital-art-constructor__category" id="1">
                    <div class="digital-art-constructor__category-image" style="background-image: url({{ asset('/storage/couple.jpg') }} );"></div>
                    <span class="digital-art-constructor__category-name">Couple portrait packages</span>
                </div>
                <div class="digital-art-constructor__category" id="2">
                    <div class="digital-art-constructor__category-image" style="background-image: url({{ asset('/storage/family.jpg') }} );"></div>
                    <span class="digital-art-constructor__category-name">Family portrait packages</span>
                </div>
            </div>
        </div>

        {{-- rate --}}
        <div class="digital-art-constructor__step">
            <div class="digital-art-constructor__step-header">
                <div class="digital-art-constructor__step-number"><span>2</span></div>
                <div class="digital-art-constructor__step-text">
                    <span class="digital-art-constructor__step-text-header">Select rate</span>
                </div>
            </div>
            <div class="digital-art-constructor__rates">
                <div class="digital-art-constructor__rate digital-art-constructor__rate--selected" id="0">
                    <span class="digital-art-constructor__rate-name digital-art-constructor__rate-name--classic">Classic</span>
                    <ul class="digital-art-constructor__rate-items">
                        <li>free flash card by mail</li>
                        <li>digital portrait</li>
                    </ul>
                </div>
                <div class="digital-art-constructor__rate" id="1">
                    <span class="digital-art-constructor__rate-name digital-art-constructor__rate-name--gold">Gold</span>
                    <ul class="digital-art-constructor__rate-items">
                        <li>free flash card by mail</li>
                        <li>digital portrait</li>
                        <li>12×16 canvas print</li>
                        <li>12×16 frame</li>
                    </ul>
                </div>
                <div class="digital-art-constructor__rate" id="2">
                    <span class="digital-art-constructor__rate-name digital-art-constructor__rate-name--platinum">Platinum</span>
                    <ul class="digital-art-constructor__rate-items">
                        <li>free flash card by mail</li>
                        <li>digital portrait</li>
                        <li>12×16 canvas print</li>
                        <li>12×16 frame</li>
                        <li>30×40 canvas print</li>
                        <li>30×40 frame</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- images --}}
        <div class="digital-art-constructor__step">
            <div class="digital-art-constructor__step-header">
                <div class="digital-art-constructor__step-number"><span>3</span></div>
                <div class="digital-art-constructor__step-text">
                    <span class="digital-art-constructor__step-text-header">Upload your photos</span>
                    <span class="digital-art-constructor__step-text-hint">You can select up to 4 photos. It is allowed to upload an photo in the format jpg, png or bmp.</span>
                </div>
            </div>
            <div class="digital-art-constructor__images">
                <div class="digital-art-constructor__add-image-block">
                    <div class="digital-art-constructor__add-image">
                        <i class="material-icons">add_circle</i>
                        <span>Add image</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- wishes --}}
        <div class="digital-art-constructor__step">
            <div class="digital-art-constructor__step-header">
                <div class="digital-art-constructor__step-number"><span>4</span></div>
                <div class="digital-art-constructor__step-text">
                    <span class="digital-art-constructor__step-text-header">Enter your email and add comment for the artist</span>
                </div>
            </div>
            <div class="digital-art-constructor__wishes">
                <input class="digital-art-constructor__email" type="text" placeholder="Email">
                <textarea class="digital-art-constructor__wishes-textarea" placeholder="Comments"></textarea>
            </div>
        </div>

        {{-- summary --}}
        <div class="digital-art-constructor__wrap-summary">
            <div class="digital-art-constructor__summary">
                <div class="digital-art-constructor__price-block">
                    <div class="digital-art-constructor__summary-left-column">
                        <span class="digital-art-constructor__price-text">Summary</span>
                        <span class="digital-art-constructor__price">$500</span>
                    </div>
                    <div class="digital-art-constructor__summary-right-column">
                        <button class="digital-art-constructor__order-button"><i class="material-icons">send</i>Order digital art</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection