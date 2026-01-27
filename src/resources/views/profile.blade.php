@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="register-form__content">
  <div class="register-form__heading">
    <h1>プロフィール設定</h1>
  </div>
  <form class="form" action="{{ route('profile.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form__group">
      <div class="form__group-title">
        <label for="image" class="form__btn--item">画像を選択する</label>
      </div>
      <div class="form__group-content">
        <div class="form__input--image">
            <input type="file" id="image" name="image" accept="image/*" hidden>
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">ユーザー名</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="user_name" value="{{ old('user_name') }}" />
        </div>
        <div class="form__error">
          @error('user_name')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">郵便番号</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="zip_code" value="{{ old('zip_code') }}" />
        </div>
        <div class="form__error">
          @error('zip-code')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">住所</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="address" value="{{ old('address') }}" />
        </div>
        <div class="form__error">
          @error('address')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">建物名</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="building" value="{{ old('building') }}" />
        </div>
      </div>
    </div>
    <div class="form__button">
      <button class="form__button-submit" type="submit">更新する</button>
    </div>
  </form>
</div>
@endsection
