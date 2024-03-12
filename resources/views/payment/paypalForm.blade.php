<form action="{{ $paypalUrl }}" method="post">
    <input type="hidden" name="charset" value="utf-8">
    <input type="hidden" name="cmd" value="_cart">
    <input type="hidden" name="upload" value="1">
    <input type="hidden" name="business" value="{{ $merchantEmail }}">
    <input type="hidden" name="notify_url" value="{{ $paypalNotifyUrl }}">
    <input type="hidden" name="return" value="{{ $paypalReturnUrl }}">
    <input type="hidden" name="currency_code" value="{{ $paypalCurrencyCode }}">
    @for ($i = 0; $i < count($items); ++$i)
        <input type="hidden" name="item_name_{{ $i + 1 }}" value="{{ $items[$i]->name }}">
        <input type="hidden" name="item_number_{{ $i + 1 }}" value="1">
        <input type="hidden" name="amount_{{ $i + 1 }}" value="{{ $items[$i]->price }}">
    @endfor
    {{--<input type="hidden" name="lc" value="US">--}}
    <input type="hidden" name="custom" value="{{ $customData }}">
    <input type="submit" id="pay" hidden>
</form>