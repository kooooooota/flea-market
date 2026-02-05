@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<section class="item-detail">
    <div class="item-detail__img">
        <img
            class="item-detail__item-img" 
            src="{{ $item?->image_path
                ? asset('storage/' . $item->image_path)
                : asset('images/default.png') }}"
            alt="商品画像"
        >
    </div>
    <div class="item-detail__content">
        <h1 class="item-detail__main-title">{{ $item->name ?? '未設定' }}</h1>
        <p class="item-detail__brand-name">{{ $item->brand_name ?? '未設定' }}</p>
        <p class="item-detail__price">{{ $item->price ?? '未設定' }}</p>
        <div class="item-detail__reaction">
            <form class="item-detail__likes-form" action="{{ route('items.favorite', $item) }}" method="post">
                @csrf
                <button class="item-detail__likes-btn" type="submit">
                    @if(Auth::user()?->favoriteProducts->contains($item->id))
                    <img class="item-detail__likes-img" src="{{ asset('images/heart-logo-default_pink.png') }}" alt="いいね済画像">
                    @else
                    <img class="item-detail__likes-img" src="{{ asset('images/heart-logo-default.png') }}" alt="未いいね画像">
                    @endif
                    <p class="item-detail__likes-count">{{ $item->likes_count }}</p>
                </button>
            </form>
            <div class="item-detail__comment-icon">
                <img class="item-detail__comment-img" src="{{ asset('images/speech-bubble.png') }}" alt="コメント画像">
                <p class="item-detail__comment-count">{{ $item->comments_count }}</p>
            </div>
        </div>
        <a class="item-detail__checkout-link" href="{{ route('items.checkout', $item) }}">購入手続きへ</a>
        <h2 class="item-detail__section-title">商品説明</h2>
        <p class="item-detail__description">{{ $item->description ?? '未設定' }}</p>
        <h2 class="item-detail__section-title">商品の情報</h2>
        <div class="item-detail__category">
            <h3 class="item-detail__sub-title">カテゴリー</h3>
            @foreach($item->categories as $category)
                <span class="item-detail__category-name">{{ $category->name }}</span>
            @endforeach
        </div>
        <div class="item-detail__condition">
            <h3 class="item-detail__sub-title">商品の状態</h3>
            <p class="item-detail__condition-label">{{ $item->condition->label() ?? '未設定' }}</p>
        </div>
        <h2 class="item-detail__section-title--comment">コメント({{ $item->comments_count }})</h2>
        @foreach ($item->comments as $comment)
        <div class="item-detail__comment">
            <div class="item-detail__comment-user">
                <div class="{{ $comment->user?->profile?->image_path ? '' : 'is-empty' }}">
                    @if($comment->user?->profile?->image_path)
                        <img class="item-detail__user-img" src="{{ asset('storage/' . $comment->user->profile->image_path) }}" alt="プロフィール画像">
                    @endif
                </div>
                <p class="item-detail__comment-name">{{ $comment->user->name }}</p>
            </div>
            <p class="item--detail__comment-body">{{ $comment->body }}</p>
        </div>
        @endforeach
        <h3 class="item-detail__sub-title">商品へのコメント</h3>
        <form class="item-detail__comment-form" action="{{ route('items.comment', $item) }}" method="post">
            @csrf
            <textarea class="item-detail__comment-textarea" name="body" id=""></textarea>
            <p class="item-detail__error-message">
                @error('body')
                {{ $message }}
                @enderror
            </p>
            <button class="item-detail__comment-btn" type="submit">コメントを送信する</button>
        </form>
    </div>
</section>
@endsection