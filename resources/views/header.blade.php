<div class="wrap-header">
    <div class="header">
        <a class="header__logo" href="{{ route('page::home') }}">
            <img class="header__logo-image" src="{{ asset('storage/logo.png') }}">
        </a>
        <div class="header__title-and-menu">
            <div class="header__title-block">
                <div class="header__title">
                    <span>Ialama</span>
                    <span>Art Gallery & Shop</span>
                </div>
            </div>
            <div class="header__menu-block">
                <div class="header__top-menu">
                    <a class="header__cart-button" href="{{ route('cart::cart') }}"><i class="material-icons">shopping_cart</i><span class="header__cart-count"><span>0</span></span></a>
                </div>
                <ul class="header__menu">
                    @php
                        $menuItems = [
                            [
                                'href' => route('page::home'),
                                'name' => __('ui.menuHome'),
                            ],
                            [
                                'href' => route('page::digital'),
                                'name' => __('ui.menuDigitalArt'),
                            ],
                            [
                                'href' => route('painting::paintings'),
                                'name' => __('ui.menuPaintings'),
                            ],
                            [
                                'href' => route('page::services'),
                                'name' => __('ui.menuServices'),
                            ],
                            [
                                'href' => route('page::about'),
                                'name' => __('ui.menuAboutUs'),
                            ],
                            [
                                'href' => route('page::contact'),
                                'name' => __('ui.menuContactUs'),
                            ],
                        ];
                        $url = explode('/', Request::url());
                        $url = array_slice($url, 0, 4);
                        $url = implode('/', $url);
                    @endphp
                    @foreach ($menuItems as $item)
                        <li class="header__menu-item">
                            @if ($url == $item['href'])
                                <a class="header__menu-link header__menu-link--active" href="{{ $item['href'] }}">{{ $item['name'] }}</a>
                            @else
                                <a class="header__menu-link" href="{{ $item['href'] }}">{{ $item['name'] }}</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <a class="sticky-cart" href="{{ route('cart::cart') }}" style="display: none;"><i class="material-icons">shopping_cart</i><span>0</span></a>
</div>

<div class="mobile-header">
    <a class="mobile-header__logo" href="{{ route('page::home') }}">
        <img src="{{ asset('storage/logo.png') }}">
    </a>
    <div class="mobile-header__title">
        <span>Ialama</span>
        <span>Art Gallery & Shop</span>
    </div>
    <div class="mobile-header__menu-button">
        <i class="material-icons">menu</i>
    </div>
    <ul class="mobile-header__menu mobile-header__menu--hidden">
        <div class="mobile-header__menu-header">
            <a class="mobile-header__cart" href="{{ route('cart::cart') }}"><i class="material-icons">shopping_cart</i><span>0</span></a>
            <div class="mobile-header__menu-close-button">
                <span>Close menu</span>
                <i class="material-icons">close</i>
            </div>
        </div>
        @php
            $menuItems = [
                [
                    'href' => route('page::home'),
                    'name' => __('ui.menuHome'),
                ],
                [
                    'href' => route('page::digital'),
                    'name' => __('ui.menuDigitalArt'),
                ],
                [
                    'href' => route('painting::paintings'),
                    'name' => __('ui.menuPaintings'),
                ],
                [
                    'href' => route('page::services'),
                    'name' => __('ui.menuServices'),
                ],
                [
                    'href' => route('page::about'),
                    'name' => __('ui.menuAboutUs'),
                ],
                [
                    'href' => route('page::contact'),
                    'name' => __('ui.menuContactUs'),
                ],
            ];
            $url = explode('/', Request::url());
            $url = array_slice($url, 0, 4);
            $url = implode('/', $url);
        @endphp
        @foreach ($menuItems as $item)
            <li class="mobile-header__menu-item">
                @if ($url == $item['href'])
                    <span class="mobile-header__menu-link mobile-header__menu-link--active">{{ $item['name'] }}</span>
                @else
                    <a class="mobile-header__menu-link" href="{{ $item['href'] }}">{{ $item['name'] }}</a>
                @endif
            </li>
        @endforeach
    </ul>
</div>