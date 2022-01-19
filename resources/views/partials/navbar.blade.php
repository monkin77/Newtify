<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark" id="navbarContainer">
        <div class="container-fluid py-4">
            <a id="logo" class="navbar-brand text-center w-25" href="{{ url('/') }}">Newtify</a>
            
            <div class="d-flex d-md-none justify-content-end align-items-center flex-grow-1">
                @if (Auth::check())
                    <a id="createArticleIcon" class="nav-item" href="{{ route('createArticle') }}">
                        <i class="purpleLink fas fa-plus-circle fa-2x"></i>
                    </a>
                    <div class="nav-item mx-3 position-relative">
                        <i class="fas fa-bell" onclick="console.log('Clicked')"></i>
                        @if ($newNotifications)
                            <div class="border border-4 border-warning rounded-circle position-absolute start-100"></div>
                        @endif
                    </div>
                    <div class="nav-item mx-3 position-relative">
                        <i class="fas fa-envelope" onclick="console.log('Clicked')"></i>
                        @if ($newMessages)
                            <div class="border border-4 border-warning rounded-circle position-absolute start-100"></div>
                        @endif
                    </div>

                    <div id="dropdownContainer" class="nav-item dropdown ms-3 me-4">
                        <img id="dropdownAvatar" class="nav-link px-0 dropdown-toggle py-0" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false"
                            src={{ isset(Auth::user()->avatar) ? asset('storage/avatars/' . Auth::user()->avatar) : $userImgPHolder }}
                            onerror="this.src='{{ $userImgPHolder }}'" />

                        <ul id="mainDropdown" class="dropdown-menu dropdown-menu-dark text-center"
                            aria-labelledby="dropdownAvatar">
                            @if (Auth::user()->is_admin)
                                <li><a class="dropdown-item dropdown-custom-item"
                                        href="{{ route('admin') }}">Admin Panel
                                    </a></li>
                            @endif

                            <a class="dropdown-item dropdown-custom-item"
                                href="{{ url('/user/' . Auth::id()) }}">My
                                Profile</a>
                            <br>
                            <li class="col text-center">
                                <a class="btn btn-lightPurple btn-lg py-2 px-4" href="{{ route('logout') }}"> Logout </a>
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

            <button class="navbar-toggler m-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse w-75" id="navbarSupportedContent">
                <form id="searchForm" class="d-flex flex-row align-items-center border"
                    action="{{ route('search') }}">
                    <i class="fas fa-search ms-4 submit" type="submit"></i>
                    <input class="form-control no-border flex-grow-1 my-0 ms-3 bg-dark" type="search"
                        placeholder="Search" name="query" autocomplete="off" value="{{ old('query') }}" />
                    <input type="hidden" name="type" value="{{ old('type') ? old('type') : 'articles' }}" />

                    <div class="dropdown" id="searchDropdown">
                        <button id="searchDropdownButton"
                            class="btn btn-outline-light border-start dropdown-toggle my-0 pe-0" type="button"
                            data-bs-toggle="dropdown">{{ old('type') == 'users' ? 'Users' : 'Articles' }}</button>

                        <ul class="dropdown-menu dropdown-menu-dark w-100 text-center"
                            aria-labelledby="searchDropdownButton">
                            <li><a class="dropdown-item dropdown-custom-item" onclick="setSearchType(this)">Articles</a>
                            </li>
                            <li><a class="dropdown-item dropdown-custom-item" onclick="setSearchType(this)">Users</a>
                            </li>
                        </ul>
                    </div>
                </form>
                
                <div class="d-none d-md-flex justify-content-end align-items-center" id="userSectionNav">
                    @if (Auth::check())
                        <a id="createArticleIcon" class="nav-item mx-4" href="{{ route('createArticle') }}">
                            <i class="purpleLink fas fa-plus-circle fa-3x"></i>
                        </a>
                        <div class="nav-item mx-4 position-relative">
                            <i class="fas fa-bell" onclick="console.log('Clicked')"></i>
                            @if ($newNotifications)
                                <div class="border border-4 border-warning rounded-circle position-absolute start-100"></div>
                            @endif
                        </div>
                        <div class="nav-item mx-4 position-relative">
                            <i class="fas fa-envelope" onclick="console.log('Clicked')"></i>
                            @if ($newMessages)
                                <div class="border border-4 border-warning rounded-circle position-absolute start-100"></div>
                            @endif
                        </div>

                        <div id="dropdownContainer" class="nav-item dropdown ms-5">
                            <img id="dropdownAvatar" class="nav-link px-0 dropdown-toggle py-0" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                src={{ isset(Auth::user()->avatar) ? asset('storage/avatars/' . Auth::user()->avatar) : $userImgPHolder }}
                                onerror="this.src='{{ $userImgPHolder }}'" />

                            <ul id="mainDropdown" class="dropdown-menu dropdown-menu-dark text-center"
                                aria-labelledby="dropdownAvatar">
                                @if (Auth::user()->is_admin)
                                    <li><a class="dropdown-item dropdown-custom-item"
                                            href="{{ route('admin') }}">Admin Panel
                                        </a></li>
                                @endif

                                <a class="dropdown-item dropdown-custom-item"
                                    href="{{ url('/user/' . Auth::id()) }}">My
                                    Profile</a>
                                <br>
                                <li class="col text-center">
                                    <a class="btn btn-lightPurple btn-lg py-2 px-4" href="{{ route('logout') }}"> Logout </a>
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
</header>
