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
                <span class="dashboard-order__data-row-key">Category</span>
                <span class="dashboard-order__data-row-value">{{ $category }}</span>
            </div>
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">Rate</span>
                <span class="dashboard-order__data-row-value">{{ $rate }}</span>
            </div>
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">Email</span>
                <span class="dashboard-order__data-row-value">{{ $order->email }}</span>
            </div>
            <div class="dashboard-order__data-row">
                <span class="dashboard-order__data-row-key">Comment</span>
                <span class="dashboard-order__data-row-value">{{ $order->comment or '···' }}</span>
            </div>
        </div>
        <h2 class="dashboard-order__header">Attached photos</h2>
        <div class="dashboard-order__images">
            @foreach ($images as $image)
                <a class="dashboard-order__image" href="{{ asset('storage/digital/'.$image) }}" target="blank">
                    <div class="dashboard-order__wrap-preview">
                        <div class="dashboard-order__preview" style="background-image: url({{ asset('storage/digital/'.$image) }});"></div>
                    </div>
                    <div class="dashboard-order__overlay">
                        <i class="material-icons">open_in_new</i>
                        <span>Open in new tab</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

@endsection