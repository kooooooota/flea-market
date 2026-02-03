@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="profile">
    <img
        src="{{ $profile?->image_path
            ? asset('storage/' . $profile->image_path)
            : asset('images/default.png') }}"
        alt="プロフィール画像" 
        style="width: 30px; height: 30px; object-fit: cover;"
    >

    <p>{{ $profile->user_name ?? '未設定' }}</p>
    <a href="{{ route('profile.edit') }}">編集する</a>
    <div class="item-list__page">
        <div class="item-list__page-link">
            <a href="{{ route('profile.show', ['page' => 'sell']) }}" class="profile-list__page-sell {{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ route('profile.show', ['page' => 'buy']) }}" class="profile-list__page-buy {{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
        </div>
    </div>
    <div class="item-list__content">
        <div class="item-list__grid">
            @foreach ($items as $item)
            <div class="item-list__item">
                <a class="item-list__item-link" href="{{ route('items.show', $item) }}">
                    <img
                    class="item-list__item-img"
                    src="{{ $item?->image_path
                    ? asset('storage/' . $item->image_path)
                    : asset('images/default.png') }}"
                    alt="商品画像"
                    >
                    <span class="item-list__item-name" href="{{ route('items.show', $item) }}">{{ $item->name }}</span>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
