@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp
@push('seo')
    @section('title', $product->title . ' | Shentviton')
@section('meta_description', Str::limit(strip_tags($product->description), 150))
@section('meta_keywords', $product->title . ', დიზაინი, უნიკალური პროდუქტი')
@section('og_title', $product->title . ' | Shentviton')
@section('og_description', Str::limit(strip_tags($product->description), 150))
@section('og_image', asset('storage/' . $product->image1))
@endpush
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
<div class="container customization-container">
    <div class="row flex-column-reverse flex-md-row">
        <div class="col-md-5">

            {{-- TAB CONTROLS --}}
            @include('components.tab-controls')

            {{-- PRODUCT --}}
            @include('components.product-tab', [
                'product' => $product,
                'productArray' => $productArray,
            ])

            {{-- UPLOADER --}}
            @include('components.uploader-tab')

            {{-- CLIPARTS --}}
            @include('components.cliparts-tab')

            {{-- TEXT --}}
            @include('components.text-tab')

            @include('components.zoom')

            <button id="addToCart" class="d-none d-md-block btn save-btn">დაამატე კალათაში</button>
        </div>

        @include('components.canvas-tab', [
            'product' => $product,
            'productArray' => $productArray,
        ])
    </div>
</div>

<script>
    $(function() {
        $('#clipartCategory').chosen({
            width: '100%'
        });

        // Rebind change handler after Chosen initializes
        $('#clipartCategory').on('change', function() {
            selectedCategory = this.value;
            clipartOffset = 0;
            $('#clipartContainer').html('');
            loadCliparts();
        });
    });

    function openCity(evt, cityName) {
        // Skip this function entirely on mobile
        if (window.innerWidth <= 1024) {
            return;
        }

        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    document.getElementById("defaultOpen").click();
    document.addEventListener("DOMContentLoaded", function() {
        const incrementBtn = document.getElementById("increment");
        const decrementBtn = document.getElementById("decrement");
        const quantityInput = document.getElementById("quantity");
        const totalPrice = document.getElementById("total-price");
        const basePrice = {{ intval($product->price) }};

        function updateQuantityAndPrice() {
            const qty = parseInt(quantityInput.value) || 1;
            quantityInput.value = qty;

            const total = qty * basePrice;
            totalPrice.textContent = new Intl.NumberFormat().format(total) + " ლარი";

            localStorage.setItem("quantity", qty);
        }

        incrementBtn.addEventListener("click", function() {
            quantityInput.value = parseInt(quantityInput.value || 1) + 1;
            updateQuantityAndPrice();
        });

        decrementBtn.addEventListener("click", function() {
            let current = parseInt(quantityInput.value || 1);
            if (current > 1) {
                quantityInput.value = current - 1;
                updateQuantityAndPrice();
            }
        });

        quantityInput.addEventListener("change", updateQuantityAndPrice);

        // Initial update
        updateQuantityAndPrice();
    });

    let clipartOffset = 0;
    const clipartLimit = 10;
    let selectedCategory = "all";

    function loadCliparts() {
        axios.get('{{ route('cliparts.load') }}', {
            params: {
                offset: clipartOffset,
                category: selectedCategory
            }
        }).then(response => {
            const container = document.getElementById('clipartContainer');
            container.insertAdjacentHTML('beforeend', response.data.html);

            clipartOffset += clipartLimit;

            if (!response.data.hasMore) {
                document.getElementById('loadMoreCliparts').style.display = 'none';
            } else {
                document.getElementById('loadMoreCliparts').style.display = 'block';
            }
        });
    }

    document.getElementById("clipartCategory").addEventListener("change", function() {
        selectedCategory = this.value;
        clipartOffset = 0;
        document.getElementById("clipartContainer").innerHTML = "";
        loadCliparts();
    });

    document.addEventListener("DOMContentLoaded", function() {
        loadCliparts();

        document.getElementById('loadMoreCliparts').addEventListener('click', function() {
            loadCliparts();
        });

        // Re-bind dynamic clipart click
        document.getElementById("clipartContainer").addEventListener("click", function(e) {
            if (e.target && e.target.classList.contains("clipart-img")) {
                window.addClipArtToCanvas.call(e.target);
            }
        });
    });
</script>
@endsection
