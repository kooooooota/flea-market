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
      </div>
    </div>
  </header>
  <main>
    @yield('content')
  </main>
</body>

</html>
