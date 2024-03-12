@extends ('admin.layout')

@section ('title', 'Dashboard')

@section ('panel')
    
    <a class="dashboard-header__button dashboard-header__button--red" href="{{ route('logout') }}">⚡ Log out</a>

@endsection

@section ('content')

    <div class="dashboard">
        <a class="dashboard__folder" href="{{ route('admin::paintings') }}">
            <span class="dashboard__name">Paintings</span>
            <span class="dashboard__count">{{ $paintingsCount }}</span>
        </a>
        <a class="dashboard__folder" href="{{ route('admin::orders') }}">
            <span class="dashboard__name">Orders</span>
            <span class="dashboard__count">{{ $ordersCount }}</span>
        </a>
        <a class="dashboard__folder" href="{{ route('admin::featured') }}">
            <span class="dashboard__name">Featured artists</span>
            <span class="dashboard__count">{{ $featuredCount }}</span>
        </a>
        <a class="dashboard__folder" href="{{ route('admin::events') }}">
            <span class="dashboard__name">Events</span>
            <span class="dashboard__count">{{ $eventsCount }}</span>
        </a>
    </div>

@endsection