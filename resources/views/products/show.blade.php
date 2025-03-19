@extends('layouts.app')

@section('title', $product->title)

@section('content')


<div class="row">
    <!-- Left Sidebar -->
    <div class="col-md-4">
        <h3>{{ $product->title }}</h3>
        <p><strong>Price:</strong> ${{ $product->price }}</p>
        <form action="{{ route('cart.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <!-- Size Selection -->
            <div class="mb-3">
                <label for="size" class="form-label">Size:</label>
                <select name="size" id="size" class="form-select">
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>
            </div>

            <!-- Comment Field -->
            <div class="mb-3">
                <label for="comment" class="form-label">Comment:</label>
                <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
            </div>

            <!-- Quantity Adjustment -->
            <div class="mb-3 d-flex align-items-center">
                <label for="quantity" class="form-label me-2">Quantity:</label>
                <button type="button" class="btn btn-secondary me-2" id="decrement">-</button>
                <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control" style="width: 60px;">
                <button type="button" class="btn btn-secondary ms-2" id="increment">+</button>
            </div>

            <!-- Add to Cart Button -->
            <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
        </form>


        <br> <br>


        <a href="{{ route('products.customize', $product->id) }}" class="btn btn-primary">
            <i class="fas fa-paint-brush"></i> Customize This Product
        </a>
    </div>





    <!-- Right Section -->
    <div class="col-md-8 text-center">
        <img src="{{ asset('storage/' . $product->image1) }}" id="product-image" class="img-fluid" alt="{{ $product->title }}">
        <div class="mt-3">
            <button type="button" class="btn btn-secondary" id="zoom-out">-</button>
            <span id="zoom-level">100%</span>
            <button type="button" class="btn btn-secondary" id="zoom-in">+</button>
        </div>
    </div>
</div>
@endsection
