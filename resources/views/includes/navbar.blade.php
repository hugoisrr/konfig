<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand" @guest href="{{ url('/') }}" @else href="{{ route('courses.index') }}" @endguest>
            {{ config('app.name', 'Konfigurator') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
<!--                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>-->
                    {{--@if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif--}}
<!--                    <li class="nav-item">
                        <a class="nav-link" href="/about">About</a>
                    </li>-->
                @else
                    @if(Auth::user()->id == 1)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">OAuth Dashboard</a>
                        </li>
                    @endif
                    @if(Auth::user()->type == 1)
                        <li class="nav-item">
                            <a class="nav-link" target="_blank" href="/telescope">Telescope</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">Benutzer</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('courses.index') }}">Kurse</a>
                    </li>
                    @if(Auth::user()->type == 1)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showBaseCourse') }}">Base Kurs</a>
                        </li>
                     @endif
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
