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

    <p>ユーザー名：{{ $profile->user_name ?? '未設定' }}</p>
    <p>郵便番号：{{ $profile->zip_code ?? '未設定' }}</p>
    <p>住所：{{ $profile->address ?? '未設定' }}</p>
    <p>建物名：{{ $profile->building ?? '未設定' }}</p>

    <a href="{{ route('profile.edit') }}">編集する</a>
</div>

@endsection
