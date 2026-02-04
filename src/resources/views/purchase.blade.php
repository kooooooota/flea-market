@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form action="{{ route('items.purchase', ['item' => $item->id]) }}" method="post">
<img
        src="{{ $item?->image_path
            ? asset('storage/' . $item->image_path)
            : asset('images/default.png') }}"
        alt="商品画像"
>
<h1>{{ $item->name ?? '未設定' }}</h1>
<p>{{ $item->price ?? '未設定' }}（税込）</p>
<h2>支払い方法</h2>
<select name="payment_method" id="payment_method">    
    <option disabled selected hidden>選択してください</option>
    @foreach($paymentMethods as $paymentMethod)
    <option value="{{ $paymentMethod->id }}" {{ old('payment_method') == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->method }}</option>
    @endforeach
</select>
@error('payment_method')
{{ $message }}
@enderror
<h2>配送先</h2>
@if($shipping)
<p>{{ $shipping['zip_code'] }}</p>
<p>{{ $shipping['address'] }}</p>
<p>{{ $shipping['building'] }}</p>
@else
<p>{{ auth()->user()->profile?->zip_code }}</p>
<p>{{ auth()->user()->profile?->address }}</p>
@endif
<a href="{{ route('items.to_address_form', ['item' => $item->id]) }}">変更する</a>
<h2>商品代金</h2>
<p>￥{{ $item->price }}</p>
<h2>支払い方法</h2>
<div>
    <span id="output">（未選択）</span>
</div>
    @csrf
    <button type="submit">購入する</button>
</form>

<script>
    document.getElementById('payment_method').addEventListener('change', function() {
    const selectedText = this.options[this.selectedIndex].text;
    document.getElementById('output').textContent = selectedText;
});
</script>
@endsection