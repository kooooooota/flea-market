@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="profile">
    <div class="profile__top">
        <div class="profile__user">
            <div class="{{ $profile?->image_path ? '' : 'is-empty' }}">
                @if($profile?->image_path)
                <img class="profile__user-img" src="{{ asset('storage/' . $profile->image_path) }}" alt="プロフィール画像">
                @endif
            </div>
            <h1 class="profile__user-name">{{ $profile->user_name ?? '未設定' }}</h1>
        </div>
        <a class="profile__edit-link" href="{{ route('profile.edit') }}">プロフィールを編集</a>
    </div>
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
                @if($item->sold)
                <p class="item-list__item-sold">Sold</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
