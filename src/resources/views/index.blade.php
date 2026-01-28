@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="flex border-b">
    <a href="{{ route('items.index', ['keyword' => request('keyword')]) }}" class="p-4 {{ $tab === 'all' ? 'active' : '' }}">おすすめ</a>
    <a href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}" class="p-4 {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
</div>

<div>
    @foreach ($items as $item)
        <img
        src="{{ $item?->image_path
            ? asset('storage/' . $item->image_path)
            : asset('images/default.png') }}"
        alt="商品画像"
    >
        <a href="{{ route('items.show', $item) }}" class="btn btn-primary">{{ $item->name }}</a>
        @if($item->sold)
        <p>sold</p>
        @endif
    @endforeach
</div>


@endsection