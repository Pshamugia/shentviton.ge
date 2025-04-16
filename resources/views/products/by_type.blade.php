@extends('layouts.app') {{-- or your main layout --}}

@section('title', 'შენი დიზაინი, შენი სტილი | Shentviton')
@section('meta_description', 'შენ თვითონ გააფორმე მაისური, ჰუდი, კეპი ან ქეისი. შენი დიზაინი — შენი სტილი.')
@section('meta_keywords', 'დიზაინი, მაისური, ჰუდი, კეპი, უნიკალური პროდუქტი, ტანსაცმელი')
@section('og_title', 'გააფორმე შენ თვითონ | Shentviton')
@section('og_description', 'შეიმუშავე შენი სტილი და ატარე უნიკალური სამოსი')
@section('og_image', asset('storage/designs/shentviton_logo.png'))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="justify-content-start">
            <i class="bi bi-app-indicator"></i>  {{ $subtype }} / {{ $type === 'all' ? 'ყველა' : $type }}
        </div>
    
        @if($type !== 'all')
    <div class="d-flex justify-content-end">
        <form method="GET" action="{{ route('products.byType', $type) }}">
            {{-- Keep current subtype and sort --}}
            <input type="hidden" name="subtype" value="{{ $subtype }}">
            <input type="hidden" name="sort" value="{{ $sort }}">

            <select name="size" id="size-select" onchange="this.form.submit()" class="chosen-select">
                <option value="">
                    @if($type === 'ქეისი') აირჩიეთ მოდელი  
                    @elseif($type === 'მაისური') აირჩიეთ ზომა 
                    @elseif($type === 'პოლო') აირჩიეთ ზომა 
                    @else {{ "" }}
                    @endif
                </option>

                @foreach ($allSizes as $size)
                    <option value="{{ $size }}" {{ $selectedSize == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
@endif


<script>
    $(document).ready(function () {
        $('.chosen-select').chosen({
            width: '300px',
            placeholder_text_single: 'აირჩიეთ'
        });
    });
</script>

    </div>
    
    
    <div class="row">
        @forelse ($products as $product)
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
        <div class="out-of-stock-section">
            <p>მარაგი ამ ეტაპზე ამოწურულია. მალე განახლდება</p>
            <img src="{{ asset('storage/designs/out-stock.png') }}" alt="Out of stock">
           
        </div>
        @endforelse
    </div>
   <div class="d-flex justify-content-left">
    {{ $products->withQueryString()->links('pagination.custom-pagination') }}
</div>
</div> 
@endsection
