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
                <form class="d-flex justify-content-center align-items-center m-0 col-5">
                    <input class="form-control me-2 w-75 m-0" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success m-0" type="submit">Search</button>
                </form>
                <div class="d-flex justify-content-end align-items-center col">
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
                                    <a class="btn btn-outline-secondary btn-lg" href="{{ url('/logout') }}"> Logout </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
