@extends ('layout')

@section ('title', __('title.cart'))

@section ('content')
    
    <div class="page-header page-header--cart">
        <h1 class="page-header__title">{{ __('title.cart') }}</h1>
        @if ($cartIsEmpty)
            <p class="page-header__text">Your cart is empty :(</p>
        @else
            <p class="page-header__text"></p>
        @endif
    </div>

    @if (!$cartIsEmpty)
        <div class="cart">
            <div class="cart__table-header">
                <span class="cart__table-header-block cart__table-header-block--name">Name</span>
                <span class="cart__table-header-block cart__table-header-block--type">Type</span>
                <span class="cart__table-header-block cart__table-header-block--price">Price</span>
            </div>
            <div class="cart__items">
                @foreach ($items as $item)
                    <div class="cart__item">
                        <div class="cart__item-block cart__item-block--name">
                            @if ($item['link'])
                                <a href="{{ route('painting::painting', ['link' => $item['link']]) }}">{{ $item['name'] }}</a>
                            @else
                                <span>{{ $item['name'] }}</span>
                            @endif
                        </div>
                        <span class="cart__item-block cart__item-block--type">{{ $item['type'] }}</span>
                        <span class="cart__item-block cart__item-block--price">${{ $item['price'] }}</span>
                        <div class="cart__item-wrap-remove"><span class="cart__item-remove" title="Remove this item"><input class="cart-item-id" type="text" value="{{ $item['id'] }}" hidden><i class="material-icons">remove_shopping_cart</i></span></div>
                    </div>
                @endforeach
            </div>
            <div class="cart__summary">
                <span class="cart__summary-price">${{ $summary }}</span>
            </div>
            <div class="payment-form">
                <div class="payment-form__methods">
                    <div class="payment-form__method payment-form__method--active" data="paypal">
                        <span class="payment-form__method-radio"></span>
                        <span class="payment-form__method-icon payment-form__method-icon--paypal"></span>
                        <span class="payment-form__method-text">Paypal</span>
                    </div>
                    <div class="payment-form__method payment-form__method" data="stripe">
                        <span class="payment-form__method-radio"></span>
                        <span class="payment-form__method-icon payment-form__method-icon--stripe"></span>
                        <span class="payment-form__method-text">Visa / Mastercard</span>
                    </div>
                </div>
                <script src="https://js.stripe.com/v3/"></script>
                <input class="payment-form__stripe-publishable-key" type="text" value="{{ $stripePublishableKey }}" hidden>
                <div class="wrap-stripe-form"></div>
                <div class="payment-form__row">
                    <div class="payment-form__column">
                        <input class="payment-form__input payment-form__input--name" type="text" placeholder="Name">
                    </div>
                </div>
                <div class="payment-form__row">
                    <div class="payment-form__column">
                        <input class="payment-form__input payment-form__input--address" type="text" placeholder="Address">
                    </div>
                </div>
                <div class="payment-form__row">
                    <div class="payment-form__column">
                        <input class="payment-form__input payment-form__input--city" type="text" placeholder="City">
                    </div>
                </div>
                <div class="payment-form__row">
                    <div class="payment-form__column payment-form__column--half">
                        <input class="payment-form__input payment-form__input--state" type="text" placeholder="State">
                    </div>
                    <div class="payment-form__column payment-form__column--half">
                        <input class="payment-form__input payment-form__input--zip" type="text" placeholder="ZIP Code">
                    </div>
                </div>
                <div class="payment-form__row">
                    <div class="payment-form__column">
                        <input class="payment-form__input payment-form__input--email" type="email" placeholder="Email">
                    </div>
                </div>
                <button class="payment-form__submit">Checkout</button>
                <div class="payment-form__error"></div>
            </div>
            <div class="payment-form__hidden-form"></div>
        </div>
    @endif

@endsection