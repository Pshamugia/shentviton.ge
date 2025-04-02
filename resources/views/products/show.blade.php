@extends('layouts.app')

@section('title', $product->title)

@section('content')


<div class="row">
    <!-- Left Sidebar -->
    <div class="col-md-4 bg-light p-4 rounded shadow-sm">
        <h4 class="mb-3">{{ $product->title }}</h4>
        <p><strong>ფასი:</strong> {{ intval($product->price) }} ლარი</p>
    
        <form action="{{ route('cart.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
    
            <!-- Size Selection -->
           
            
            @php
    // These are the sizes selected in admin, stored in the DB as comma-separated string
    $availableSizes = explode(',', $product->size);
    $selectedSize = old('size', $selectedSize ?? null); // for preselecting in form if needed
@endphp

<div class="mb-3">
    <label for="size" class="form-label">აირჩიეთ</label>
    <select name="size" id="size" class="form-select" required>
        <option value="">აირჩიეთ</option>

        @foreach ($availableSizes as $size)
            <option value="{{ $size }}" {{ $selectedSize == $size ? 'selected' : '' }}>
                {{ $size }}
            </option>
        @endforeach
    </select>
</div>
            
           
    
            <!-- Quantity Adjustment -->
            <div class="mb-3">
                <label class="form-label d-block">რაოდენობა:</label>
                <div class="input-group">
                    <button type="button" class="btn btn-outline-secondary" id="decrement">-</button>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control text-center" style="max-width: 70px;">
                    <button type="button" class="btn btn-outline-secondary" id="increment">+</button>
                </div>
            </div>
    
            <!-- Zoom Controls -->
            <div class="mb-4">
                <label class="form-label d-block">გადიდება:</label>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" id="zoom-out">-</button>
                    <span id="zoom-level" class="mx-2">100%</span>
                    <button type="button" class="btn btn-outline-secondary" id="zoom-in">+</button>
                </div>
            </div>
    
            <!-- Add to Cart Button -->
            @if($product->subtype == 'მზა')
            <button type="submit" class="btn btn-primary w-100 mb-2">კალათაში დამატება</button>
            @endif
        </form>
    
        <!-- Customize Button -->
        @if($product->subtype !== 'მზა')
        <a href="{{ route('products.customize', $product->id) }}" class="btn btn-success w-100">
            <i class="fas fa-paint-brush"></i> გააფორმე შენ თვითონ
        </a>
        @endif
    </div>
    





    <!-- Right Section -->
    <div class="col-md-8 text-center">
        <img src="{{ asset('storage/' . $product->image1) }}" id="product-image" class="img-fluid" alt="{{ $product->title }}">
    
    </div>
</div>
@endsection
