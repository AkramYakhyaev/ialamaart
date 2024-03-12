@extends ('admin.layout')

@section ('title', '#'.$order->id)

@section ('panel')
    
    <a class="dashboard-header__button dashboard-header__button--red" id="order__delete-button">✖ Delete order</a>

@endsection

@section ('content')

    <div class="dashboard-order">
        <h2 class="dashboard-order__header">Information</h2>
        <div class="dashboard-order__data">
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">ID</span>
                <span class="dashboard-order__data-row-value dashboard-order__id">{{ $order->id }}</span>
            </div>
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">Price</span>
                @php
                    if ($order->status === 0) {
                        $status = '<span class="dashboard-order__status dashboard-order__status--not-paid">not paid</span>';
                    } elseif ($order->status === 1) {
                        $status = '<span class="dashboard-order__status dashboard-order__status--paid">paid</span>';
                    } else {
                        $status = '<span class="dashboard-order__status">?</span>';
                    }
                @endphp
                <span class="dashboard-order__data-row-value">${{ $order->price }} ({!! $status !!})</span>
            </div>
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">Payment</span>
                @if ($order->status === 1)
                    <span class="dashboard-order__data-row-value">{!! ucfirst($orderData['method']) !!} ({{ $orderData['orderId'] }})</span>
                @else
                    <span class="dashboard-order__data-row-value">{!! ucfirst($orderData['method']) !!}</span>
                @endif
            </div>
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">Name</span>
                <span class="dashboard-order__data-row-value">{{ $orderData['name'] }}</span>
            </div>
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">Email</span>
                <span class="dashboard-order__data-row-value">{{ $order->email }}</span>
            </div>
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">Address</span>
                <span class="dashboard-order__data-row-value">{{ $orderData['address'] }}</span>
            </div>
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">City</span>
                <span class="dashboard-order__data-row-value">{{ $orderData['city'] }}</span>
            </div>
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">State</span>
                <span class="dashboard-order__data-row-value">{{ $orderData['state'] }}</span>
            </div>
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">ZIP</span>
                <span class="dashboard-order__data-row-value">{{ $orderData['zip'] }}</span>
            </div>
        </div>
        <h2 class="dashboard-order__header">Paintings</h2>
        <div class="dashboard-paintings" style="margin: 0;">
            @foreach ($paintings as $painting)
                <a class="dashboard-paintings__item" href="{{ route('admin::editPainting', ['link' => $painting->link]) }}">
                    <div class="dashboard-paintings__wrap-preview">
                    @if (file_exists('storage/paintings/'.$painting->id.'/0.jpg'))
                        <div class="dashboard-paintings__preview" style="background-image: url({{ asset('/storage/paintings/'.$painting->id.'/0.jpg') }});"></div>
                    @else
                        <div class="dashboard-paintings__preview" style="background-image: url({{ asset('/storage/paintings/default.jpg') }});"></div>
                    @endif
                    <div class="dashboard-paintings__overlay">
                        <span class="dashboard-paintings__price">${{ $painting->price }}</span>
                        <span class="dashboard-paintings__category">{{ $painting->category()->first()->name }}</span>
                    </div>
                </div>
                <span class="dashboard-paintings__name">{{ $painting->name }}</span>
                </a>
            @endforeach
        </div>
    </div>

@endsection