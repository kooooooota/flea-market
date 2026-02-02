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
        @if(Auth::user()?->favoriteProducts->contains($item->id))
        <img src="{{ asset('images/heart-logo-default_pink.png') }}" alt="いいね済画像">
        @else
        <img src="{{ asset('images/heart-logo-default.png') }}" alt="未いいね画像">
        @endif
    </button>
</form>
<p>{{ $item->likes_count }}</p>
<img
        src="{{ asset('images/speech-bubble.png') }}"
        alt="コメント画像"
    >
<p>{{ $item->comments_count }}</p>
<form action="{{ route('items.checkout', $item) }}">
    <button>購入手続きへ</button>
</form>
<h2>商品説明</h2>
<p>{{ $item->description ?? '未設定' }}</p>
<h2>商品の情報</h2>
<h3>カテゴリー</h3>
<div>
    @foreach($item->categories as $category)
        <span>{{ $category->name }}</span>
    @endforeach
</div>
<h3>商品の状態</h3>
<p>{{ $item->condition->label() ?? '未設定' }}</p>
<h2>コメント</h2>
<p>({{ $item->comments_count }})</p>
@foreach ($item->comments as $comment)
<div class="comment">
    <img 
        src="{{ $comment?->user->profile->image_path 
            ? asset('storage/' . $comment->user->profile->image_path) 
            : asset('images/default-avatar.png') }}" 
        alt="プロフィール画像"
    >
    <p>{{ $comment->user->name }}</p>
    <p>{{ $comment->body }}</p>
</div>
@endforeach
<h3>商品へのコメント</h3>
<form action="{{ route('items.comment', $item) }}" method="post">
    @csrf
    <textarea name="body" id=""></textarea>
    @error('body')
        {{ $message }}
    @enderror
    <button type="submit">コメントを送信する</button>
</form>
@endsection