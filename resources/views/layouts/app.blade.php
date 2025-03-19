<!DOCTYPE html>
<html lang="ka">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   

    @vite([
        'resources/js/app.js',
        'resources/js/product.js',
        'resources/css/sass/app.scss',
        'resources/css/app.css',
        ])
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset('storage/designs/shentviton_logo.png') }}" width="180px"> </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">საწყისი</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">კალათა</a></li>
                    @guest
                        <!-- Show Login & Register when not logged in -->
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @else
                        <!-- Show Logout when logged in -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid" style="background: url('storage/designs/shentviton_banner.jpg') no-repeat center center; background-size: cover; background-attachment: fixed; height: 180px;">
   
    </div>

    <div class="container mt-4">
        @yield('content')
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-1">&copy; {{ date('Y') }} SHENTVITON.GE - All Rights Reserved.</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                <li class="list-inline-item"><a href="{{ route('cart.index') }}" class="text-white">Cart</a></li>
                <li class="list-inline-item"><a href="{{ route('home') }}#contact" class="text-white">Contact</a></li>
            </ul>
            <p class="mt-2">
                Follow us on:
                <a href="#" class="text-white mx-2"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
            </p>
        </div>
    </footer>
</body>

</html>
