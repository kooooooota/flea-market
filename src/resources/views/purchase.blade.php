@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form class="checkout-form" action="{{ route('purchase.checkout', $item->id) }}" method="post">
    @csrf
    <div class="checkout-form__purchase-info">
        <div class="checkout-form__purchase-top">
            <img
                    class="checkout-form__purchase-img"
                    src="{{ asset('storage/' . $item->image_path) }}"
                    alt="商品画像"
            >
            <div class="checkout-form__purchase-content">
                <h1 class="main-title">{{ $item->name ?? '未設定' }}</h1>
                <p class="checkout-form__purchase-price">{{ isset($item->price) ? number_format($item->price) : '未設定' }}</p>
            </div>
        </div>
        <div class="checkout-form__payment">
            <h2 class="section-title">支払い方法</h2>
            <select class="checkout-form__payment-select" name="payment_method" id="payment_method">    
                <option disabled selected hidden>選択してください</option>
                @foreach($paymentMethods as $paymentMethod)
                <option value="{{ $paymentMethod->id }}" {{ old('payment_method') == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->method }}</option>
                @endforeach
            </select>
        </div>
        <p class="checkout-form__error-message">
            @error('payment_method')
            {{ $message }}
            @enderror
        </p>
        <div class="checkout-form__address-form">
            <div class="checkout-form__address-info">
                <h2 class="section-title">配送先</h2>
                @if($shipping)
                <p class="checkout-form__address-zip">{{ $shipping['zip_code'] }}</p>
                <div class="checkout-form__address">
                    <p class="checkout-form__address-address">{{ $shipping['address'] }}</p>
                    <p class="checkout-form__address-building">{{ $shipping['building'] }}</p>
                </div>
                @else
                <p class="checkout-form__address-zip">{{ auth()->user()->profile?->zip_code }}</p>
                <div class="checkout-form__address">
                    <p class="checkout-form__address-address">{{ auth()->user()->profile?->address }}</p>
                    <p class="checkout-form__address-building">{{ auth()->user()->profile?->building }}</p>
                </div>
                @endif
            </div>
            <a class="checkout-form__address-link" href="{{ route('items.to_address_form', ['item' => $item->id]) }}">変更する</a>
        </div>
        <p class="checkout-form__error-message">
            @error('address')
            {{ $message}}
            @enderror
        </p>
    </div>
    <div class="checkout-form__confirmation">
        <div class="checkout-form__confirmation-content">
            <p class="checkout-form__confirmation-title">商品代金</p>
            <p class="checkout-form__confirmation-price">{{ isset($item->price) ? number_format($item->price) : '未設定' }}</p>
        </div>
        <div class="checkout-form__confirmation-content">
            <p class="checkout-form__confirmation-title">支払い方法</p>
            <span class="checkout-form__confirmation-payment" id="output">（未選択）</span>
        </div>
        <button class="checkout-form__confirmation-btn" type="submit">購入する</button>
    </div>
</form>

<script>
    document.getElementById('payment_method').addEventListener('change', function() {
    const selectedText = this.options[this.selectedIndex].text;
    document.getElementById('output').textContent = selectedText;
});
</script>
@endsection