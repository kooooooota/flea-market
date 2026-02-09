@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-form">
    <h1 class="main-title">商品の出品</h1>
    <form class="sell-form__form" action="{{ route('items.sell') }}" method="post" enctype="multipart/form-data">
        @csrf
        <label class="sell-form__label">商品画像</label>
        <div class="sell-form__select-img">
            <label class="sell-form__select-btn" for="image">画像を選択する</label>
            <input class="sell-form__input" type="file" id="image" name="image" accept=".jpeg,.jpg,.png" hidden>
        </div>
        <p class="sell-form__error-message">
        @error('image')
        {{ $message }}
        @enderror
        </p>
        <h2 class="section-title">商品の詳細</h2>
        <label class="sell-form__label">カテゴリー</label>
        <div class="sell-form__category">
            @foreach($categories as $category)
                <label class="sell-form__category-select">
                    <input class="sell-form__category-input" type="checkbox" name="category[]" value="{{ $category->id }}"
                        {{ (is_array(old('category')) && in_array($category->id, old('category'))) ? 'checked' : '' }}>
                    <span class="sell-form__category-name">{{ $category->name }}</span>
                </label>
            @endforeach
        </div>
        <p class="sell-form__error-message">
        @error('category')
        {{ $message }}
        @enderror
        </p>
        <label class="sell-form__label">商品の状態</label>
        <select class="sell-form__condition" name="condition">
            <option value="" selected disabled hidden>選択してください</option>
            @foreach($conditions as $condition)
                <option class="sell-form__condition-option" value="{{ $condition->value }}">{{ $condition->label() }}</option>
            @endforeach
        </select>
        <p class="sell-form__error-message">
        @error('condition')
        {{ $message }}
        @enderror
        </p>
        <h2 class="section-title">商品名と説明</h2>
        <label class="sell-form__label" for="name">商品名</label>
        <input class="sell-form__input" type="text" name="name" id="name">
        <p class="sell-form__error-message">
        @error('name')
        {{ $message }}
        @enderror
        </p>
        <label class="sell-form__label" for="brand_name">ブランド名</label>
        <input class="sell-form__input" type="text" name="brand_name" id="brand_name">
        <p class="sell-form__error-message">
        @error('brand_name')
        {{ $message }}
        @enderror
        </p>
        <label class="sell-form__label" for="description">商品の説明</label>
        <textarea class="sell-form__textarea" name="description" id="description"></textarea>
        <p class="sell-form__error-message">
        @error('description')
        {{ $message }}
        @enderror
        </p>
        <label class="sell-form__label" for="price">販売価格</label>
        <div class="sell-form__input-yen">
            <input class="sell-form__input-price" type="text" name="price" id="price">
        </div>
        <p class="sell-form__error-message">
        @error('price')
        {{ $message }}
        @enderror
        </p>
        <button class="sell-form__btn" type="submit">出品する</button>
    </form>
</div>
@endsection