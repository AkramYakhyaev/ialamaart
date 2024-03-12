@extends ('admin.layout')

@section ('title', 'Orders')

@section ('content')

    <div class="dashboard-orders">
        <div class="dashboard-orders__header">
            <span class="dashboard-orders__header-text dashboard-orders__header-text--id">ID</span>
            <span class="dashboard-orders__header-text dashboard-orders__header-text--price">Price</span>
            <span class="dashboard-orders__header-text dashboard-orders__header-text--type">Type</span>
            <span class="dashboard-orders__header-text dashboard-orders__header-text--status">Status</span>
            <span class="dashboard-orders__header-text dashboard-orders__header-text--date">Date</span>
        </div>
        @foreach ($orders as $order)
            <a class="dashboard-orders__item" href="{{ route('admin::order', ['id' => $order->id]) }}">
                <span class="dashboard-orders__id">#{{ $order->id }}</span>
                <span class="dashboard-orders__price">${{ $order->price }}</span>
                <span class="dashboard-orders__type">{!! ucfirst($order->type) !!}</span>
                @if ($order->status === 0)
                    <span class="dashboard-orders__status dashboard-orders__status--not-paid">Not paid</span>
                @elseif ($order->status === 1)
                    <span class="dashboard-orders__status dashboard-orders__status--paid">Paid</span>
                @else
                    <span class="dashboard-orders__status">?</span>
                @endif
                <span class="dashboard-orders__date">{{ $order->created_at->diffForHumans() }}</span>
            </a>
        @endforeach
    </div>

@endsection