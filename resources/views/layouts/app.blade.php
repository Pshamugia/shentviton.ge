<!DOCTYPE html>
<html lang="ka">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-0X7N9V9R66"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-0X7N9V9R66');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- ✅ Facebook Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', 'Shentviton - შენი დიზაინი, შენი სტილი')" />
    <meta property="og:description" content="@yield('og_description', 'გააფორმე შენ თვითონ და ატარე უნიკალური პროდუქტი')" />
    <meta property="og:image" content="@yield('og_image', asset('storage/designs/shentviton_logo.png'))" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="ka_GE" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Shentviton - შენი დიზაინი, შენი სტილი')">
    <meta name="twitter:description" content="@yield('og_description', 'გააფორმე შენ თვითონ და ატარე უნიკალური პროდუქტი')">
    <meta name="twitter:image" content="@yield('og_image', asset('storage/designs/shentviton_logo.png'))">
    <link rel="icon" href="{{ asset('storage/designs/favicon.ico') }}" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron&family=Monoton&family=Creepster&family=Rubik+Bubbles&family=Gloria+Hallelujah&family=Black+Ops+One&family=Lobster&family=PlaywriteIN&family=Erica+One&family=Alk-rounded&family=Berkshire+Swash&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

    @vite(['resources/css/sass/app.scss', 'resources/css/app.css', 'resources/css/tabmenu.css', 'resources/js/app.js', 'resources/js/product.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">




    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Shentviton",
      "url": "https://shentviton.ge",
      "logo": "{{ asset('storage/designs/shentviton_logo.png') }}",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+995-593-922217",
        "contactType": "customer service",
        "areaServed": "GE"
      },
      "sameAs": [
        "https://www.facebook.com/YOUR_PAGE_NAME"
      ]
    }
    </script>

    @stack('schema')
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
                                style="background-color: #aacadf;  border-radius: 25px;
                                padding: 6px 15px; 
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                                color:black;
                                transition: background 0.3s ease;">
                                გააფორმე შენ თვითონ
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="readyDesignsDropdown">

                                <li><a class="dropdown-item"
                                        href="{{ route('products.byType', ['type' => 'all', 'subtype' => 'custom']) }}">ყველას
                                        ჩვენება</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('products.byType', ['type' => 't-shirt', 'subtype' => 'custom']) }}">მაისური</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('products.byType', ['type' => 'polo', 'subtype' => 'custom']) }}">პოლო
                                        მაისური</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('products.byType', ['type' => 'bomber', 'subtype' => 'custom']) }}">ბომბერი</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('products.byType', ['type' => 'hoodie', 'subtype' => 'custom']) }}">ჰუდი</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('products.byType', ['type' => 'cap', 'subtype' => 'custom']) }}">კეპი</a>
                                </li>

                                <li><a class="dropdown-item"
                                    href="{{ route('products.byType', ['type' => 'bag', 'subtype' => 'custom']) }}">
                                    ჩანთა</a></li>
                            <li>
                                
                                    <a class="dropdown-item"
                                        href="{{ route('products.byType', ['type' => 'phone-case', 'subtype' => 'custom']) }}">ტელეფონის
                                        ქეისი</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="readyDesignsDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            მზა დიზაინები
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="readyDesignsDropdown">
                            <li><a class="dropdown-item" href="{{ route('products.byType', 'all') }}">ყველა</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('products.byType', ['type' => 't-shirt']) }}">მაისური</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('products.byType', ['type' => 'cap']) }}">კეპი</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('products.byType', ['type' => 'phone-case']) }}">ტელეფონის
                                    ქეისი</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link cart-link" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge bg-danger" id="cart-count">{{ session('cart_count', 0) }}</span>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link search-toggle" href="#" role="button">
                            <i class="fas fa-search"></i>
                        </a>
                        <div class="search-dropdown">
                            <form action="{{ route('search') }}" method="GET" class="d-flex">
                                <input type="text" name="query" class="form-control me-2"
                                    placeholder="საძიებო სიტყვა..." required>
                                <button type="submit" class="btn search-button">ძებნა</button>
                            </form>
                        </div>
                    </li>



                    <!--      @guest
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
                    @endguest -->

                </ul>
            </div>
        </div>
    </nav>







    <div class="container mt-4" style="margin-top:40px !important; position: relative;">
        @yield('content')
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container" style="padding: 20px">
            <div class="row text-center">
                <!-- First Column -->
                <div class="col-md-4 text-start">

                    <i class="bi bi-telephone"></i> &nbsp; 593922217 <br>
                    <i class="bi bi-envelope"></i> &nbsp; info@shentviton.ge
                </div>

                <!-- Second Column -->
                <div class="col-md-4 text-start">
                    <div style="margin-bottom:10px"> </div>
                    <ul class="list-inline">
                        <li class="list-inline-item"><a href="{{ route('home') }}" class="nav-link"> <i
                                    class="bi bi-house-door"></i> &nbsp; საწყისი</a>
                        </li>
                        <br>
                        <li class="list-inline-item"><a href="{{ route('cart.index') }}" class="nav-link"> <i
                                    class="bi bi-cart2"></i> &nbsp; კალათა</a>
                        </li><br>
                        <li class="list-inline-item"><a href="{{ route('terms') }}" class="nav-link"> <i
                                    class="bi bi-newspaper"></i> &nbsp; წესები და
                                პირობები</a></li><br>

                    </ul>
                </div>

                <!-- Third Column -->
                <div class="col-md-4 text-start">

                    <div style="margin-bottom:10px"> გამოგვყევი </div>
                    <a href="https://www.facebook.com/shentviton.ge" target="_blank" class="text-white"><i
                            class="fab fa-facebook custom-icon-facebook"></i></a>
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
            // Navbar close on item click (for mobile)
            let navLinks = document.querySelectorAll(".navbar-nav a");
            let navbarCollapse = document.querySelector(".navbar-collapse");
            let navbarToggler = document.querySelector(".navbar-toggler");


            // Scroll effect for navbar
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Search button toggle
            const toggle = document.querySelector('.search-toggle');
            const dropdown = document.querySelector('.search-dropdown');

            if (toggle && dropdown) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
                });

                document.addEventListener('click', function(e) {
                    if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.style.display = 'none';
                    }
                });
            }

            // Add to cart forms
            const forms = document.querySelectorAll(".add-to-cart-form");
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

            forms.forEach(form => {
                const button = form.querySelector(".add-to-cart-btn");

                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    const cartItemId = button.dataset.cartItemId;

                    if (button.classList.contains("btn-success") && cartItemId) {
                        // REMOVE from cart
                        fetch(`/cart/${cartItemId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: new URLSearchParams({
                                    _method: 'DELETE'
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    button.innerHTML =
                                        '<i class="fas fa-shopping-cart"></i> კალათაში დამატება';
                                    button.classList.remove('btn-success');
                                    button.classList.add('btn-primary');
                                    delete button.dataset.cartItemId;

                                    document.getElementById('cart-count').textContent = data
                                        .cartCount;
                                }
                            });
                    } else {
                        // ADD to cart
                        fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: formData
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    button.innerHTML =
                                        '<i class="fas fa-check-circle"></i> დამატებულია';
                                    button.classList.remove('btn-primary');
                                    button.classList.add('btn-success');
                                    button.dataset.cartItemId = data.cartItemId;

                                    console.log("data.cartCOunt: ", data
                                        .cartCount)

                                    document.getElementById('cart-count').textContent = data
                                        .cartCount;
                                }
                            });
                    }
                });
            });
        });
    </script>







</body>

</html>
