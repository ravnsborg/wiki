<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Wiki') }}</title>

    <!-- JS Scripts -->
    <script type="text/javascript" src="{!! asset('js/library/jquery/jquery3.5.1.min.js') !!}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

    <script type="text/javascript" src="{!! asset('js/library/fontawesome/all.min5.13.0.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/library/bootstrap4.5.2/bootstrap.min.js') !!}"></script>
{{--    <script type="text/javascript" src="{!! asset('js/library/jquery/jquery.highlight.js') !!}"></script>--}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    <!-- Css Styles -->
    <link href="{{ URL::asset('css/library/fontawesome/all.min5.13.0.css') }}" rel="stylesheet" media="all">
    <link href="{{ URL::asset('css/library/bootstrap4.5.2/bootstrap.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>

    {{--https://github.com/laracasts/flash--}}
    @include('flash::message')

    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <h2>{{ config('app.name', 'Wiki') }}</h2>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto"></ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    @if(isset($links) && $links)
        <div class="topnav">
            @yield('search')
            @foreach($links as $link)
                <a href="{{ $link->url }}" target="_blank">{{ $link->title }}</a>
                @if (!$loop->last)
                    |
                @endif
            @endforeach


            <div
                data-toggle="modal"
                data-target="#contentModalContent"
                data-target="#contentModal"
                data-operation="add_content"
                class="topnav_add_content"
                data-backdrop="static"
                data-keyboard="false"
            >
                <i class="fa fa-plus fa-lg add_content" data-operation="add_content"> Add Content</i>
            </div>

        </div>
    @endif

    <div class="left_column">
        @yield('categories')
    </div>

    <div class="row">
        <section>
            <div id="main_body" class="container">
                @yield('content')
            </div>
        </section>
    </div>

</body>
</html>


