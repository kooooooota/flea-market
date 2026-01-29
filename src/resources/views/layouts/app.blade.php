<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flea Market</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @yield('css')
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <a class="header__logo" href="/"><img class="header__logo-img" src="images/header-logo.png" alt="サイトロゴ"></a>
      <form class="search-bar" action="{{ route('items.index') }}" method="get">
        <input class="search-bar__input" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
      </form>
      <div class="menu">
        @if (Auth::check())
        <form class="menu__logout" action="/logout" method="post">
          @csrf
          <button class="menu__logout-button" type="submit">ログアウト</button>
        </form>
        @else
        <a class="menu__login-link" href="/login">ログイン</a>
        @endif
        <a class="menu__mypage-link" href="/mypage">マイページ</a>
        <a class="menu__sell-link" href="/sell">出品</a>
      </div>
    </div>
  </header>
  <main>
    @yield('content')
  </main>
</body>

</html>
