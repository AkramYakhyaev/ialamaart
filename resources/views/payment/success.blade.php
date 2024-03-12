@extends ('layout')

@section ('title', __('title.successPayment'))

@section ('content')
    
    <div class="indev">
        <span class="indev__text">{{ __('ui.paymentSuccessPageText') }}</span>
        <span class="indev__description">{!! __('ui.paymentSuccessPageDescription') !!}
    </div>

@endsection