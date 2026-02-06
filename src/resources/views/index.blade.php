@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="item-list">
    <div class="item-list__tab">
        <div class="item-list__tab-link">
            <a href="{{ route('items.index', ['keyword' => request('keyword')]) }}" class="item-list__tab-all {{ $tab === 'all' ? 'active' : '' }}">おすすめ</a>
            <a href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}" class="item-list__tab-mylist {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
    </div>
    <div class="item-list__content">
        <div class="item-list__grid">
            @foreach ($items as $item)
            <div class="item-list__item">
                @if($item->sold)
                <div class="item-list__item-link">
                    <img
                    class="item-list__item-img--sold"
                    src="{{ $item?->image_path
                    ? asset('storage/' . $item->image_path)
                    : asset('images/default.png') }}"
                    alt="商品画像"
                    >
                    <span class="item-list__item-name" href="{{ route('items.show', $item) }}">{{ $item->name }}</span>
                </div>
                <p class="item-list__item-sold">Sold</p>
                @else
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
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection