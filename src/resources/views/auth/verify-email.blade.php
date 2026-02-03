@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<form action="{{ route('verification.send') }}" method="post">
    @csrf
    <p>登録していただいたメールアドレスに認証メールを送付しました。<br>メール認証を完了してください。</p>
    <a href="http://localhost:8025">認証はこちらから</a>
    <button type="submit">認証メールを再送する</button>
</form>

@endsection