<!DOCTYPE html>
<html lang="ka">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('resources/css/custom.css') }}">
    <link rel="icon" href="{{ asset('storage/designs/favicon.ico') }}" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @vite(['resources/js/app.js', 'resources/js/product.js', 'resources/css/sass/app.scss', 'resources/css/app.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="z-index: 101 !important">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('storage/designs/shentviton_logo.png') }}" width="180px">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Ensure collapse div is inside the container -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">საწყისი</a></li>
                    <li class="nav-item dropdown">
                        <div class="dropdown-toggle-wrapper">
                            <a class="nav-link dropdown-toggle" href="#" id="readyDesignsDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                style="background-color: #d6336c; border-radius:5px; color:#fff; display:block; text-align:left;">
                                გააფორმე შენ თვითონ
                            </a>
                        </div>
                        <ul class="dropdown-menu" aria-labelledby="readyDesignsDropdown">
                            
                            <li><a class="dropdown-item" href="{{ route('products.byType', ['type' => 'all', 'subtype' => 'custom']) }}">ყველას ჩვენება</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.byType', ['type' => 't-shirt', 'subtype' => 'custom']) }}">მაისური</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.byType', ['type' => 'cap', 'subtype' => 'custom']) }}">კეპი</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.byType', ['type' => 'phone-case', 'subtype' => 'custom']) }}">ტელეფონის ქეისი</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="readyDesignsDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            მზა დიზაინები
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="readyDesignsDropdown">
                            <li><a class="dropdown-item" href="{{ route('products.byType', 'all') }}">ყველა</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.byType', ['type' => 't-shirt']) }}">მაისური</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.byType', ['type' => 'cap']) }}">კეპი</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.byType', ['type' => 'phone-case']) }}">ტელეფონის ქეისი</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            კალათა (<span id="cart-count">{{ session('cart_count', 0) }}</span>)
                        </a>
                    </li>


                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @else
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







    <div class="container mt-4">
        @yield('content')
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container" style="padding: 20px 0 0 0">
            <div class="row text-center">
                <!-- First Column -->
                <div class="col-md-4 text-start">

                    <i class="bi bi-telephone"></i> &nbsp; 593922217 <br>
                    <i class="bi bi-envelope"></i> &nbsp; info@shentviton.ge
                </div>

                <!-- Second Column -->
                <div class="col-md-4 text-start">
                    <ul class="list-inline">
                        <li class="list-inline-item"><a href="{{ route('home') }}" class="text-white">საწყისი</a>
                        </li>
                        <br>
                        <li class="list-inline-item"><a href="{{ route('cart.index') }}"
                                class="text-white">კალათა</a>
                        </li><br>
                        <li class="list-inline-item"><a href="{{ route('home') }}" class="text-white">წესები და
                                პირობები</a></li><br>
                        <li class="list-inline-item"><a href="{{ route('home') }}" class="text-white">კონტაქტი</a>
                        </li>
                    </ul>
                </div>

                <!-- Third Column -->
                <div class="col-md-4 text-start">

                    <div style="margin-bottom:10px"> გამოგვყევი </div>
                    <a href="#" class="text-white"><i class="fab fa-facebook custom-icon-facebook"></i></a>
                    <a href="#" class="text-white mx-2"><i
                            class="fab fa-instagram custom-icon-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-youtube custom-icon-youtube"></i></a>

                    </p>
                </div>
            </div>
        </div>
    </footer>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let navLinks = document.querySelectorAll(".navbar-nav a");
            let navbarCollapse = document.querySelector(".navbar-collapse");
            let navbarToggler = document.querySelector(".navbar-toggler");

            navLinks.forEach(link => {
                link.addEventListener("click", function() {
                    if (window.innerWidth <= 992) {
                        console.log("Navbar item clicked, forcing close...");

                        // Force Bootstrap to fully close the navbar
                        navbarCollapse.classList.remove("collapsing", "show");
                        let bsCollapse = new bootstrap.Collapse(navbarCollapse);
                        bsCollapse.hide();

                        // Simulate toggler button click
                        navbarToggler.click();
                    }
                });
            });
        });
    </script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".add-to-cart-form").forEach(form => {
            const button = form.querySelector(".add-to-cart-btn");
            const productId = form.getAttribute("data-product-id");
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            form.addEventListener("submit", function (e) {
                e.preventDefault();

                const cartItemId = button.dataset.cartItemId;
                const formData = new FormData(form);

                if (button.classList.contains("btn-success") && cartItemId) {
                    // Remove from cart
                    fetch(`/cart/${cartItemId}`, {
                        method: 'POST',
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json"
                        },
                        body: new URLSearchParams({ _method: "DELETE" })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            button.innerHTML = '<i class="fas fa-shopping-cart"></i> კალათაში დამატება';
                            button.classList.remove("btn-success");
                            button.classList.add("btn-primary");
                            delete button.dataset.cartItemId;

                            const cartCountElem = document.getElementById("cart-count");
                            if (cartCountElem) cartCountElem.textContent = data.cartCount;
                        }
                    });
                } else {
                    // Add to cart
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json"
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            button.innerHTML = '<i class="fas fa-check-circle"></i> დამატებულია';
                            button.classList.remove("btn-primary");
                            button.classList.add("btn-success");
                            button.dataset.cartItemId = data.cartItemId;

                            const cartCountElem = document.getElementById("cart-count");
                            if (cartCountElem) cartCountElem.textContent = data.cartCount;
                        }
                    });
                }
            });
        });
    });
</script>

    




</body>

</html>
