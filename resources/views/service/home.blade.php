@extends ('layout')

@section ('title', __('title.services'))

@section ('content')
    
    {{--
    <div class="page-header">
        <h1 class="page-header__title">Services</h1>
        <p class="page-header__text">Some text is needed...</p>
    </div>
    --}}
    
    <div class="services">
        <a class="services__item" href="{{ route('page::contact') }}">
            <div class="services__item-wrap-preview">
                <div class="services__item-preview" style="background-image: url({{ asset('/storage/replica.jpg') }});"></div>
                <div class="services__item-overlay">
                    <i class="material-icons">brush</i>
                    <span class="services__item-overlay-header services__item-overlay-header--with-description">Oil paint</span>
                    <span class="services__item-overlay-text">$5 per inch</span>
                </div>
            </div>
            <span class="services__item-name">Replica</span>
        </a>
        <a class="services__item">
            <div class="services__item-wrap-preview">
                <div class="services__item-preview" style="background-image: url({{ asset('/storage/damaged-painting.jpg') }});"></div>
                <div class="services__item-overlay">
                    <i class="material-icons">lock</i>
                    <span class="services__item-overlay-header">Service not available yet</span>
                </div>
            </div>
            <span class="services__item-name">Oil paint repair</span>
        </a>
        <a class="services__item">
            <div class="services__item-wrap-preview">
                <div class="services__item-preview" style="background-image: url({{ asset('/storage/damaged-frame.jpg') }});"></div>
                <div class="services__item-overlay">
                    <i class="material-icons">lock</i>
                    <span class="services__item-overlay-header">Service not available yet</span>
                </div>
            </div>
            <span class="services__item-name">Frame repair</span>
        </a>
    </div>

@endsection