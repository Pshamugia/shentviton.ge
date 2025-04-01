@extends('layouts.app')

@section('title', 'shentviton')

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
                <img src="{{ asset('storage/designs/banner1.jpg') }}" class="d-block w-100" alt="Slide 1">
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



    <div class="hr-with-text" style="position: relative; ">
        <h2 style="position: relative; font-size: 26px; ">

            მზა დიზაინები </h2>
    </div>


    <div class="row">
        @foreach ($readyDesigns as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <a href="{{ route('products.show', $product->id) }}">
                        <img src="{{ asset('storage/' . $product->image1) }}" class="card-img-top cover_news" id="im_news"
                            alt="{{ $product->title }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p><strong>ფასი:</strong> {{ $product->price }}</p>
                        <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form" data-product-id="{{ $product->id }}">
                            @csrf
                            <input type="hidden" name="v_hash" value="{{ session('v_hash') }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="quantity" value="1" min="1" class="form-control mb-2">
                        
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
                    <a href="{{ route('products.show', $product->id) }}">
                        <img src="{{ asset('storage/' . $product->image1) }}" class="card-img-top cover_news"
                            id="im_news" alt="{{ $product->title }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p><strong>Price:</strong> ${{ $product->price }}</p>
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
@endsection 
    

    
