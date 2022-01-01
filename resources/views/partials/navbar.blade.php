<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid py-4">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse align-items-center row" id="navbarSupportedContent">
                <a class="navbar-brand h1 col" href="{{ url('/') }}">Newtify</a>

                <form id="searchForm" class="d-flex flex-row align-items-center border col-5" action="{{ route('search') }}">
                    <i class="fas fa-search ms-4 submit" type="submit"></i>
                    <input
                        class="form-control no-border flex-grow-1 my-0 ms-3 bg-dark"
                        type="search"
                        placeholder="Search"
                        name="query"
                        autocomplete="off"
                    />
                    <input type="hidden" name="type" value="articles"/>

                    <div class="dropdown" id="searchDropdown">
                        <button
                            id="searchDropdownButton"
                            class="btn btn-outline-secondary border-start dropdown-toggle my-0 pe-0"
                            type="button"
                            data-bs-toggle="dropdown"
                        >Articles</button>

                        <ul class="dropdown-menu dropdown-menu-dark w-100 text-center" aria-labelledby="searchDropdownButton">
                            <li><a class="dropdown-item search-item" onclick="setSearchType(this)">Articles</a></li>
                            <li><a class="dropdown-item search-item" onclick="setSearchType(this)">Users</a></li>
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
                                    <a class="dropdown-item" href="{{ url('/user/'.Auth::id()) }}">{{ Auth::user()->name }}</a>
                                    <br>
                                    <li class="col text-center">
                                        <a class="btn btn-outline-secondary btn-lg" href="{{ route('logout') }}"> Logout </a>
                                    </li>
                                @endif
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
