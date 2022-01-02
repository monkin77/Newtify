<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid py-4">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse row" id="navbarSupportedContent">

                <div id="logo" class="col">
                    <a id="logo" class="navbar-brand" href="{{ url('/') }}">Newtify</a>
                </div>

                <form id="searchForm" class="d-flex flex-row align-items-center border col-5" action="{{ route('search') }}">
                    <i class="fas fa-search ms-4 submit" type="submit"></i>
                    <input
                        class="form-control no-border flex-grow-1 my-0 ms-3 bg-dark"
                        type="search"
                        placeholder="Search"
                        name="query"
                        autocomplete="off"
                        value="{{ old('query') }}"
                    />
                    <input type="hidden" name="type" value="{{ old('type') ? old('type') : 'articles' }}"/>

                    <div class="dropdown" id="searchDropdown">
                        <button
                            id="searchDropdownButton"
                            class="btn btn-outline-secondary border-start dropdown-toggle my-0 pe-0"
                            type="button"
                            data-bs-toggle="dropdown"
                        >{{
                            old('type') == 'users' ?
                            'Users'
                            :
                            'Articles'
                        }}</button>

                        <ul class="dropdown-menu dropdown-menu-dark w-100 text-center" aria-labelledby="searchDropdownButton">
                            <li><a class="dropdown-item dropdown-custom-item" onclick="setSearchType(this)">Articles</a></li>
                            <li><a class="dropdown-item dropdown-custom-item" onclick="setSearchType(this)">Users</a></li>
                        </ul>
                    </div>
                </form>

                <div class="d-flex justify-content-end align-items-center col">
                    @if (Auth::check())
                        <div class="nav-item mx-4">
                            <i class="fas fa-bell" onclick="console.log('Clicked')"></i>
                        </div>
                        <div class="nav-item mx-4">
                            <i class="fas fa-envelope" onclick="console.log('Clicked')"></i>
                        </div>

                        <div id="dropdownContainer" class="nav-item dropdown ms-5">
                            <img id="dropdownAvatar" class="nav-link dropdown-toggle"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false"
                                src ={{
                                    isset(Auth::user()->avatar) ? asset('storage/avatars/'.Auth::user()->avatar) : $userImgPHolder
                                }}
                                onerror="this.src='{{ $userImgPHolder }}'"/>

                            <ul id="mainDropdown" class="dropdown-menu dropdown-menu-dark text-center" aria-labelledby="dropdownAvatar">
                                @if (Auth::user()->is_admin)
                                    <li><a
                                        class="dropdown-item dropdown-custom-item"
                                        href="{{ route('admin') }}"
                                        >Admin Panel
                                    </a></li>
                                @endif

                                <a class="dropdown-item dropdown-custom-item" href="{{ url('/user/'.Auth::id()) }}">My Profile</a>
                                <br>
                                <li class="col text-center">
                                    <a
                                        class="button button-secondary py-0 px-3"
                                        href="{{ route('logout') }}"
                                        style="font-size: 0.9em"
                                    > Logout </a>
                    <div class="nav-item mx-4">
                        <i class="fas fa-bell" onclick="console.log('Clicked')"></i>
                    </div>
                    <div class="nav-item mx-4">
                        <i class="fas fa-envelope" onclick="console.log('Clicked')"></i>
                    </div>
                    <div class="nav-item dropdown mx-4">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            @if (Auth::check())
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
<<<<<<< HEAD
                                <a class="dropdown-item" href="{{ url('/user/'.Auth::id()) }}">{{ Auth::user()->name }}</a>
                                <br>
                                <li class="col text-center">
                                    <a class="btn btn-outline-secondary btn-lg" href="{{ url('/logout') }}"> Logout </a>
=======
                                <li>
                                    <a class="button" href="{{ url('/logout') }}"> Logout </a>
                                    <span>{{ Auth::user()->name }}</span>
>>>>>>> redo changes in navbar
>>>>>>> redo changes in navbar
                                </li>

                            </ul>
                        </div>
                    @else
                        <div class="nav-item me-5">
                            <a href={{ route('login') }} class="button ms-5 mt-2">Login</a>
                            <a href={{ route('signup') }} class="button button-secondary mx-4 mt-2">Signup</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- @if (Auth::check())
    <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
    @endif--}}
</header>
