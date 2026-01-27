@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="item-index">
    <div class="tab__group">
        <label for="recommend" class="tab__group-ttl">
            <input type="radio" name="recommend" class="tab__group-ttl-input">おすすめ
        </label>
        <ul class="item-list">
            <li class="item-list__item">
                <article class="item-card">
                    <div class="item-card__img">
                        <img src="" alt="商品画像">
                    </div>
                    <div class="item-card__content">
                        <a class="item-card__content-ttl" href="" >商品名</a>
                    </div>
                </article>
            </li>
        </ul>
    </div>
    <div class="tab__group">
        <label for="recommend" class="tab__group-ttl">
            <input type="radio" name="recommend" class="tab__group-ttl-input">マイリスト
        </label>
        <ul class="item-list">
            <li class="item-list__item">
                <article class="item-card">
                    <div class="item-card__img">
                        <img src="" alt="商品画像">
                    </div>
                    <div class="item-card__content">
                        <a class="item-card__content-ttl" href="" >商品名</a>
                    </div>
                </article>
            </li>
        </ul>
    </div>

</div>
    @endsection