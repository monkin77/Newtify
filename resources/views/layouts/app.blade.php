<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="{{ asset('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/32dcfd1afe.js" crossorigin="anonymous"></script>

    <script src="https://kit.fontawesome.com/32dcfd1afe.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src={{ asset('js/app.js') }} defer>
</script>
  </head>
  <body>
    <main>
      <header>
        <h1><a href="{{ url('/cards') }}">Thingy!</a></h1>
        @if (Auth::check())
        <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
        @endif
      </header>
      <section id="content">
        @yield('content')
      </section>
    </main>
  </body>
</html>
