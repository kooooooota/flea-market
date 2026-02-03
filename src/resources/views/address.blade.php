@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-form">
  <h1>住所の変更</h1>
  <form class="address-form__form" action="{{ route('items.change_address', $item) }}" method="post" novalidate>
    @csrf
    <div class="address-form__group">
      <label class="address-form__label" for="zip_code">郵便番号</label>
      <input class="address-form__input" type="text" name="zip_code" value="{{ old('zip_code') }}" />
      <p class="address-form__error-message">
        @error('zip_code')
        {{ $message }}
        @enderror
      </p>
    </div>
    <div class="address-form__group">
      <label class="address-form__label" for="address">住所</label>
      <input class="address-form__input" type="text" name="address" />
      <p class="address-form__error-message">
        @error('address')
        {{ $message }}
        @enderror
      </p>
    </div>
    <div class="address-form__group">
      <label class="address-form__label" for="building">建物名</label>
      <input class="address-form__input" type="text" name="building" />
      <p class="address-form__error-message">
        @error('building')
        {{ $message }}
        @enderror
      </p>
    </div>
    <button class="address-form__button" type="submit">更新する</button>
  </form>
</div>
@endsection