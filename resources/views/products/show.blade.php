@extends('layouts.app')

@section('title', $product->title)

@section('og_title', $product->title)
@section('og_description', Str::limit($product->description, 150))
@section('og_image', asset('storage/' . $product->image1))

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $product->title }}",
  "image": [
    "{{ asset('storage/' . $product->image1) }}"
  ],
  "description": "{{ strip_tags(Str::limit($product->description, 150)) }}",
  "sku": "{{ $product->id }}",
  "offers": {
    "@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "GEL",
    "price": "{{ number_format($product->price, 2, '.', '') }}",
    "availability": "https://schema.org/{{ $product->quantity > 0 ? 'InStock' : 'OutOfStock' }}",
    "itemCondition": "https://schema.org/NewCondition"
  }
}
</script>
@endpush

    
    
@section('content')


<div class="row flex-column flex-md-row">



    <!-- Left Sidebar -->
    <div class="col-md-4 order-2 order-md-1 bg-light p-4 rounded shadow-sm">
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
 
@if (!empty($productArray['colors']) && count($productArray['colors']) > 0)

<div class="mb-3 pt-4 pb-4" style="background-color: #e2dfdf; padding-left:10px; border-radius: 5px"> 
    
    <label class="form-label" style="white-space: nowrap; float:left">აირჩიეთ ფერი:</label>

    <div class="row row-cols-3 g-2 mb-3">

        @foreach ($productArray['colors'] as $color)
            <div class="col text-center">
                <button class="color-option" data-color="{{ $color['color_code'] }}"
                    data-front-image="{{ asset('storage/' . $color['front_image']) }}"
                    data-back-image="{{ asset('storage/' . $color['back_image']) }}"
                    data-back-index={{ 'back-' . $color['id'] }}
                    data-front-index={{ 'front-' . $color['id'] }}
                    data-index={{ $color['id'] }}
                    style="background-color: {{ $color['color_code'] }};
                     ">
                </button>
            </div>
        @endforeach

    </div></div> @endif
    
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
    <div class="col-md-8 order-1 order-md-2 text-center">
        <img src="{{ asset('storage/' . $product->image1) }}" id="product-image" class="img-fluid" alt="{{ $product->title }}">
    
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Existing quantity logic is already above...

        const colorButtons = document.querySelectorAll('.color-option');
        const productImage = document.getElementById('product-image');

        colorButtons.forEach(button => {
            button.addEventListener('click', function () {
                const frontImage = this.getAttribute('data-front-image');
                if (frontImage) {
                    productImage.src = frontImage;
                }

                // Optional: add an active border to selected color
                colorButtons.forEach(btn => btn.classList.remove('selected-color'));
                this.classList.add('selected-color');
            });
        });
    });
    const colorButtons = document.querySelectorAll('.color-option');
        const productImage = document.getElementById('product-image');

        colorButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault(); // ✅ Prevent triggering validation

                const frontImage = this.getAttribute('data-front-image');
                if (frontImage) {
                    productImage.src = frontImage;
                }

                colorButtons.forEach(btn => btn.classList.remove('selected-color'));
                this.classList.add('selected-color');
            });
        });
    
</script>

<style>
    .color-option.selected-color {
        border: 3px solid red !important;
    }
</style>


@endsection