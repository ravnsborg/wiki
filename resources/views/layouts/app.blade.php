@php
    $pageTitle = null;
    if (isset($entitySelected->title)) {
        $pageTitle = $entitySelected->title;
    }
    $pageTitle .= config('app.name', 'Wiki');
@endphp


<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}   </title>

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

{{-- FLASH --}}
{{--https://github.com/laracasts/flash--}}
    @include('flash::message')

    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <a class="navbar-brand" href="#">{{ $pageTitle }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

{{-- AUTHENTICATION --}}

        @auth
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                    </li>

{{-- LINKS --}}
                @if(isset($links) && $links)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Links
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach($links as $link)
                                    <a class="dropdown-item"
                                       href="{{ $link->url }}"
                                       target="_blank"
                                    >
                                        {{ $link->title }}
                                    </a>
                                @endforeach
                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="/link/management">
                                        Manage Links
                                    </a>
                            </div>
                        </li>
                    @endif


                    <li class="nav-item active">
                        <div
                            class="nav-link"
                            data-toggle="modal"
                            data-target="#contentModalContent"
                            data-target="#contentModal"
                            data-operation="add_content"
                            class="topnav_add_content"
                            data-backdrop="static"
                            data-keyboard="false"
                        >
                            Add New Content
                        </div>
                    </li>
                </ul>
            @endauth

            <ul class="navbar-nav ml-auto">
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

{{-- MY ENTITIES --}}
                    @if(isset($usersEntitiesList) && $usersEntitiesList)
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                               My Entities
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @foreach($usersEntitiesList as $entity)
                                    <a class="dropdown-item"
                                       href="entity/assign/{{$entity->id}}"
                                    >
                                        {{$entity->title}}
                                    </a>
                                @endforeach

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="/entity/management">
                                    Manage Entities
                                </a>

                            </div>
                        </li>
                    @endif
{{-- SEARCH--}}
                    @yield('search')

                @endguest
            </ul>

        </div>
    </nav>


{{-- CATEGORIES LISTING --}}
    <div class="left_column">
        @yield('categories')
    </div>


{{-- ARTICLES --}}
    <section>
        @yield('content')
    </section>

</body>
</html>
