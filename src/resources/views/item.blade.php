@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<img
        src="{{ $item?->image_path
            ? asset('storage/' . $item->image_path)
            : asset('images/default.png') }}"
        alt="商品画像"
    >
<h1>{{ $item->name ?? '未設定' }}</h1>
<p>{{ $item->brand_name ?? '未設定' }}</p>
<p>{{ $item->price ?? '未設定' }}（税込）</p>
<form action="{{ route('items.favorite', $item) }}" method="post">
    @csrf
    <button type="submit" class="focus:outline-none hover:opcity-80 transition">
        @if(auth()->user()->favoriteProducts->contains($item->id))
        <img src="{{ asset('images/heart-logo-default_pink.png') }}" alt="いいね済画像">
        @else
        <img src="{{ asset('images/heart-logo-default.png') }}" alt="未いいね画像">
        @endif
    </button>
</form>
<img
        src="{{ asset('images/speech-bubble.png') }}"
        alt="コメント画像"
    >
<form action="{{ route('items.checkout', $item) }}">
    <button>購入手続きへ</button>
</form>
<h2>商品説明</h2>
<p>{{ $item->description ?? '未設定' }}</p>
<h2>商品の情報</h2>
<h3>カテゴリー</h3>
<h3>商品の状態</h3>
<p>{{ $item->condition->label() ?? '未設定' }}</p>
@endsection