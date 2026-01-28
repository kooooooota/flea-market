<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flea Market</title>
  <link rel="stylesheet" href="{{ asset('resources/css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/css/common.css') }}">
  @yield('css')
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <div class="header-utilities">
            <a class="header__logo" href="/"><img src="images/header-logo.png" alt="サイトロゴ"></a>
        <nav>
          <ul class="header-nav">
            <form action="{{ route('items.index') }}" method="get">
              <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
            </form>
            @if (Auth::check())
            <li class="header-nav__item">
              <form class="form" action="/logout" method="post">
                @csrf
                <button class="header-nav__button">ログアウト</button>
              </form>
            </li>
            @else
            <li class="header-nav__item">
              <a class="header-nav__link" href="/login">ログイン</a>
            </li>
             @endif
            <li class="header-nav__item">
              <a class="header-nav__link" href="/mypage">マイページ</a>
            </li>
            <li class="header-nav__item">
              <a class="header-nav__link" href="/sell">出品</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </header>
  <main>
    @yield('content')
  </main>
</body>

</html>
