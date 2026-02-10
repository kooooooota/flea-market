@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-form">
  <h1 class="profile-form__heading">プロフィール設定</h1>
  <form class="profile-form__form" action="{{ route('profile.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="profile-form__group-top">
      <div class="{{ $profile?->image_path ? '' : 'is-empty' }}">
          @if($profile?->image_path)
          <img class="profile__user-img" src="{{ asset('storage/' . $profile->image_path) }}" alt="プロフィール画像">
          @endif
      </div>
      <div class="profile-form__select-img">
        <label class="profile-form__select-btn" for="image_file">画像を選択する</label>
        <input class="profile-form__input" type="file" id="image_file" name="image" onchange="previewImage(this);" accept=".jpeg,.jpg,.png" hidden>
      </div>
      <p class="profile-form__error-message">
        @error('image')
        {{ $message }}
        @enderror
      </p>
    </div>
    <div class="profile-form__group">
      <label class="profile-form__label" for="user_name">ユーザー名</label>
      <input class="profile-form__input" type="text" name="user_name" value="{{ old('user_name', $profile->user_name ?? auth()->user()->name) ?? ''}}" />
      <p class="profile-form__error-message">
        @error('user_name')
        {{ $message }}
        @enderror
      </p>
    </div>
    <div class="profile-form__group">
      <label class="profile-form__label" for="zip_code">郵便番号</label>
      <input class="profile-form__input" type="text" name="zip_code" value="{{ old('zip_code', $profile->zip_code ?? '') }}" />
      <p class="profile-form__error-message">
        @error('zip_code')
        {{ $message }}
        @enderror
      </p>
    </div>
    <div class="profile-form__group">
      <label class="profile-form__label" for="address">住所</label>
      <input class="profile-form__input" type="text" name="address" value="{{ old('address', $profile->address ?? '') }}" />
      <p class="profile-form__error-message">
        @error('address')
        {{ $message }}
        @enderror
      </p>
    </div>
    <div class="profile-form__group">
      <label class="profile-form__label" for="building">建物名</label>
      <input class="profile-form__input" type="text" name="building" value="{{ old('building', $profile->building ?? '') }}" />
      <p class="profile-form__error-message">
        @error('building')
        {{ $message }}
        @enderror
      </p>
    </div>
    <button class="profile-form__button" type="submit">更新する</button>
  </form>
</div>

<script>
  function previewImage(input) {
    const preview = document.getElementById('preview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // 取得したデータURLをimgのsrcにセット
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        // ファイルがクリアされたら非表示にする
        preview.src = "";
        preview.style.display = 'none';
    }
}

</script>
@endsection
