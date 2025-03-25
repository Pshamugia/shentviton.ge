@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h4>ძიების შედეგი: "{{ $query }}"</h4>

    @if($results->count())
        <div class="row">
            @foreach($results as $product)
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
    @else
        <p>შედეგი ვერ მოიძებნა.</p>
    @endif
</div>
@endsection
