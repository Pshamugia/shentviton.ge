@extends('layouts.app')

@section('title', $product->title)

@section('og_title', $product->title)
@section('og_description', Str::limit($product->description, 150))
@section('og_image', asset('storage/' . $product->image1))

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
        <option value=""> @if($product->type === 'ქეისი') აირჩიეთ მოდელი  
            @elseif($product->type === 'მაისური') აირჩიეთ ზომა 
            @else {{ "" }}
            @endif</option>

        @foreach ($availableSizes as $size)
            <option value="{{ $size }}" {{ $selectedSize == $size ? 'selected' : '' }}>
                {{ $size }}
            </option>
        @endforeach
    </select>
</div>
            
<div class="mb-3"><label class="form-label" style="white-space: nowrap; float:left">აირჩიეთ ფერი:</label>

    @foreach ($productArray['colors'] as $color)
        <button class="color-option" data-color="{{ $color['color_code'] }}"
            data-front-image="{{ asset('storage/' . $color['front_image']) }}"
            data-back-image="{{ asset('storage/' . $color['back_image']) }}"
            data-back-index={{ 'back-' . $color['id'] }}
            data-front-index={{ 'front-' . $color['id'] }} data-index={{ $color['id'] }}
            style="background-color: {{ $color['color_code'] }}; width: 40px; height: 40px; border-radius: 50%; border: 2px solid #000; margin-bottom:22px;">
        </button>
        
    @endforeach </div>
    
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const incrementBtn = document.getElementById('increment');
        const decrementBtn = document.getElementById('decrement');
        const quantityInput = document.getElementById('quantity');
        const maxQuantity = {{ $product->quantity }};

        function updateButtons() {
            const current = parseInt(quantityInput.value) || 1;
            incrementBtn.disabled = current >= maxQuantity;
            decrementBtn.disabled = current <= 1;
        }

        incrementBtn.addEventListener('click', function () {
            let current = parseInt(quantityInput.value) || 1;

            if (current >= maxQuantity) {
                alert("მარაგში მხოლოდ " + maxQuantity + " ცალია.");
                return; // Stop here, don't increase
            }

            quantityInput.value = current + 1;
            updateButtons();
        });

        decrementBtn.addEventListener('click', function () {
            let current = parseInt(quantityInput.value) || 1;
            if (current > 1) {
                quantityInput.value = current - 1;
            }
            updateButtons();
        });

        quantityInput.addEventListener('input', function () {
            let val = parseInt(quantityInput.value);
            if (isNaN(val) || val < 1) {
                quantityInput.value = 1;
            } else if (val > maxQuantity) {
                quantityInput.value = maxQuantity;
                alert("მარაგში მხოლოდ " + maxQuantity + " ცალია.");
            }
            updateButtons();
        });

        // Initialize
        updateButtons();
    });
</script>

@endsection
