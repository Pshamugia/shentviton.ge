@extends('layouts.app') {{-- or your main layout --}}

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="justify-content-start">
            <i class="bi bi-app-indicator"></i>  {{ $subtype }} / {{ $type === 'all' ? 'ყველა' : $type }}
        </div>
    
        <div class="d-flex justify-content-end">
            <form method="GET" action="{{ route('products.byType', $type) }}">
                <input type="hidden" name="subtype" value="{{ $subtype }}">
                <select name="sort" onchange="this.form.submit()" class="form-select w-auto">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>უახლესი</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>ფასი: ზრდადობით</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>ფასი: კლებადობით</option>
                </select>
            </form>
        </div>
    </div>
    
    
    <div class="row">
        @forelse ($products as $product)
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
                        
                        @php
                            $inCart = in_array($product->id, $productIdsInCart ?? []);
                        @endphp
                    
                        <button type="submit"
                            class="btn {{ $inCart ? 'btn-success' : 'btn-primary' }} add-to-cart-btn"
                            data-cart-item-id="{{ $inCart ? $cartItems->firstWhere('product_id', $product->id)->id : '' }}">
                            {{ $inCart ? 'დამატებულია' : 'კალათაში დამატება' }}
                        </button>
                    </form>
                    
                    
                </div>
            </div>
        </div>
        @empty
            <p>ამ ტიპის პროდუქტი ვერ მოიძებნა.</p>
        @endforelse
    </div>
   <div class="d-flex justify-content-left">
    {{ $products->withQueryString()->links('pagination.custom-pagination') }}
</div>
</div> 
@endsection
