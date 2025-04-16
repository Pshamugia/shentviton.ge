@extends('layouts.app')

@section('title', 'შენი დიზაინი, შენი სტილი | Shentviton')
@section('meta_description', 'შენ თვითონ გააფორმე მაისური, ჰუდი, კეპი ან ქეისი. შენი დიზაინი — შენი სტილი.')
@section('meta_keywords', 'დიზაინი, მაისური, ჰუდი, კეპი, უნიკალური პროდუქტი, ტანსაცმელი')
@section('og_title', 'გააფორმე შენ თვითონ | Shentviton')
@section('og_description', 'შეიმუშავე შენი სტილი და ატარე უნიკალური სამოსი')
@section('og_image', asset('storage/designs/shentviton_logo.png'))

@section('content')



    <div id="imageCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#imageCarousel" data-bs-slide-to="0" class="active" aria-current="true"
                aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#imageCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>

        <!-- Slideshow -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('storage/designs/banner3.jpg') }}" class="d-block w-100" alt="Slide 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/designs/banner2.webp') }}" class="d-block w-100" alt="Slide 2">
            </div>

        </div>

        <!-- Left and right controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>



    


    <div class="category-icons">
        <a href="{{ route('products.byType', ['type' => 't-shirt', 'subtype' => 'custom']) }}" class="category-item">
            <div class="category-icon">
                <img src="{{ asset('storage/designs/tshirt-customizable.png') }}" alt="მაისური">
            </div>
            <div class="category-label">მაისური</div>
        </a>

        <a href="{{ route('products.byType', ['type' => 'polo', 'subtype' => 'custom']) }}" class="category-item">
            <div class="category-icon">
                <img src="{{ asset('storage/designs/polo-customizable.png') }}" alt="პოლო">
            </div>
            <div class="category-label">პოლო </div>
        </a>
    
        <a href="{{ route('products.byType', ['type' => 'hoodie', 'subtype' => 'custom']) }}" class="category-item">
            <div class="category-icon">
                <img src="{{ asset('storage/designs/hoodie-customizable.png') }}" alt="ჰუდი">
            </div>
            <div class="category-label">ჰუდი</div>
        </a>


        <a href="{{ route('products.byType', ['type' => 'bomber', 'subtype' => 'custom']) }}" class="category-item">
            <div class="category-icon">
                <img src="{{ asset('storage/designs/sweater-customizable.png') }}" alt="ბომბერი">
            </div>
            <div class="category-label">ბომბერი</div>
        </a>
        
        
        <a href="{{ route('products.byType', ['type' => 'bag', 'subtype' => 'custom']) }}" class="category-item">
            <div class="category-icon">
                <img src="{{ asset('storage/designs/bag-customizable.png') }}" alt="ჩანთა">
            </div>
            <div class="category-label">ჩანთა</div>
        </a>
    
    
        <a href="{{ route('products.byType', ['type' => 'phone-case', 'subtype' => 'custom']) }}" class="category-item">
            <div class="category-icon">
                <img src="{{ asset('storage/designs/mobile-case-customizable.png') }}" alt="ქეისი">
            </div>
            <div class="category-label">ქეისი</div>
        </a>
    </div>
    


    <div class="hr-with-text" style="position: relative; ">
        <h2 style="position: relative; font-size: 26px; ">

            მზა დიზაინები </h2>
    </div>

    <div class="row">
        @foreach ($readyDesigns as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($product->subtype=='მზა')
                    <a href="{{ route('products.show', $product->id) }}">
                        @else   <a href="{{ route('products.customize', $product->id) }}">
                            @endif
                        <img src="{{ asset('storage/' . $product->image1) }}" class="card-img-top cover_news" id="im_news"
                            alt="{{ $product->title }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                         <p><strong>ფასი:</strong> {{ intval($product->price) }} ლარი</p>
                        <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form" data-product-id="{{ $product->id }}">
                            @csrf
                            <input type="hidden" name="v_hash" value="{{ session('v_hash') }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="quantity" value="1" min="1" class="form-control mb-2 quantity-input" data-max="{{ $product->quantity }}">
                        
                            @php $inCart = in_array($product->id, $productIdsInCart ?? []); @endphp
                            <button type="submit"
                                class="btn w-100 add-to-cart-btn {{ $inCart ? 'btn-success' : 'btn-primary' }}"
                                data-cart-item-id="{{ $inCart ? $cartItems->firstWhere('product_id', $product->id)->id : '' }}">
                                @if($inCart)
                                    <i class="fas fa-check-circle"></i> დამატებულია
                                @else
                                    <i class="fas fa-shopping-cart"></i> კალათაში დამატება
                                @endif
                            </button>
                        </form>
                        
                        
                        
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <!-- Overlay Section -->

<div class="overlay-section">
    <div class="fixed-background" style="background: url('{{ asset('storage/designs/banner2.webp') }}') no-repeat center center; background-size: cover; background-attachment: fixed;"></div>
    <div class="overlay-content">
       <br> 
       <h2>გააფორმე შენ თვითონ</h2>
        <p><span style="font-size: 20px"> იდეალური სასაჩუქრე ვარიანტი</span></p>  
       
    </div>
</div>




    <div class="hr-with-text" style="position: relative; ">
        <h2 style="position: relative; font-size: 26px; ">

            ახალი დამატებული </h2>
    </div>

    <div class="row">
        @foreach ($customDesigns as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($product->subtype=='მზა')
                    <a href="{{ route('products.show', $product->id) }}">
                        @else   <a href="{{ route('products.customize', $product->id) }}">
                            @endif
                        <img src="{{ asset('storage/' . $product->image1) }}" class="card-img-top cover_news"
                            id="im_news" alt="{{ $product->title }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                         <p><strong>ფასი:</strong> {{ intval($product->price) }} ლარი</p>
                        <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form" data-product-id="{{ $product->id }}">
                            @csrf
                            <input type="hidden" name="v_hash" value="{{ session('v_hash') }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="quantity" value="1" min="1" class="form-control mb-2">
                            
                            @php
                                $inCart = in_array($product->id, $productIdsInCart ?? []);
                            @endphp
                        
                        <button type="submit"
                        class="btn w-100 add-to-cart-btn {{ $inCart ? 'btn-success' : 'btn-primary' }}"
                        data-cart-item-id="{{ $inCart ? $cartItems->firstWhere('product_id', $product->id)->id : '' }}">
                        @if($inCart)
                            <i class="fas fa-check-circle"></i> დამატებულია
                        @else
                            <i class="fas fa-shopping-cart"></i> კალათაში დამატება
                        @endif
                    </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.add-to-cart-form').forEach(function (form) {
                const input = form.querySelector('input[name="quantity"]');
                const maxQuantity = parseInt(input.dataset.max) || 1;
    
                input.addEventListener('input', function () {
                    let val = parseInt(input.value);
                    if (isNaN(val) || val < 1) {
                        input.value = 1;
                    } else if (val > maxQuantity) {
                        input.value = maxQuantity;
                        alert("მარაგში მხოლოდ " + maxQuantity + " ცალია.");
                    }
                });
            });
        });
    </script>
    
@endsection 
    

    
