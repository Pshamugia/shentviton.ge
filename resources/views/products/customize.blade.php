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
    <style>
        * {
            box-sizing: border-box;
        }


        /* Style the tab */
        .tab {
            float: left;
            background-color: #272c33;
            width: 30%;
            height: 400px;
            scrollbar-color: red yellow;

        }


        /* Style the buttons inside the tab */
        .tab button {
            display: block;
            background-color: inherit;
            color: black;
            padding: 22px 16px;
            width: 140px;
            border: none;
            outline: none;
            text-align: left;
            cursor: pointer;
            transition: 0.3s;
            font-size: 12px;

        }


        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current "tab button" class */
        .tab button.active {
            background-color: #ccc;
            margin-left: -20px;
        }

        /* Style the tab content */
        .tabcontent {
            float: left;
            padding: 0px 12px;
            border: 1px solid #ccc;
            background-color: #ccc;
            width: 70%;
            border-left: none;
            height: 400px;
            /* ← limits the visible height. Content taller than 300px will scroll inside the tab */
            overflow-y: auto;
            /* ← This enables vertical scrolling */
            overflow-x: hidden;
            /* ← Optional: disables horizontal scroll */
        }

        /* Make tab horizontal on mobile */
@media screen and (max-width: 768px) {
    .tab {
        float: none;
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: row;
        overflow-x: auto;
        border-bottom: 2px solid #ccc;
        background-color: #272c33;
    }

    .tab button  {
        flex: 1;
        width: 10px;
        text-align: center;
        padding: 5px 8px;
        font-size: 11px;
        color: white;
        border-bottom: 2px solid transparent;
    }


    .tab button.active {
        background-color: #444;
        border-bottom: 2px solid yellow;
        margin-left: 0;
    }

    .tabcontent {
        width: 100%;
        float: none;
        border-top: none;
        height: auto;
        max-height: 400px;
    }
    .tab::-webkit-scrollbar {
    height: 4px;
}

.tab::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 2px;
}
}
    </style>
    <div class="container">

        <div class="row flex-column-reverse flex-md-row">
                        <div class="col-md-5">

                <div class="tab">
                    <button class="tablinks icon-color" onclick="openCity(event, 'product')" id="defaultOpen">
                        <i class="bi bi-clipboard-check-fill icon-color" style="font-size: 20px"></i> <br>
                        პროდუქტი
                    </button>


                    <button class="tablinks icon-color" onclick="openCity(event, 'uploader')">
                        <i class="bi bi-card-image icon-color" style="font-size: 20px"></i> <br>
                        ატვირთე
                    </button>

                    <button class="tablinks icon-color" onclick="openCity(event, 'cliparts')">
                        <i class="fas fa-palette icon-color" style="font-size: 20px"></i> <br>
                        კლიპარტი</button>
                    <button class="tablinks icon-color" onclick="openCity(event, 'text')">
                        <i class="bi bi-chat-square-quote-fill icon-color" style="font-size:20px"></i> <br>
                        ტექსტი</button>
                </div>


                <div id="product" class="tabcontent">

                    <p>
                    <div style="text-align: right !important"> <label> <b> {{ $product->title }} </b> <span
                                class="price-color" id="total-price"> {{ intval($product->price) }} ლარი </span> </label>
                    </div>

                    @if (!empty($productArray['colors']) && count($productArray['colors']) > 0)

                        <!-- Color Selection (Left Side) -->
                        <div class="color-box" style="margin-top:50px;">
                            <div>
                                <label class="form-label" style="white-space: nowrap; float:left">აირჩიეთ ფერი:</label>

                                <div class="row row-cols-2 g-2 mb-3">
                                    @foreach ($productArray['colors'] as $color)
                                        <div class="col d-flex justify-content-center">
                                            <div style="">
                                                <button class="color-option rounded-full"
                                                    data-color="{{ $color['color_code'] }}"
                                                    data-front-image="{{ asset('storage/' . $color['front_image']) }}"
                                                    data-back-image="{{ asset('storage/' . $color['back_image']) }}"
                                                    data-back-index={{ 'back-' . $color['id'] }}
                                                    data-front-index={{ 'front-' . $color['id'] }}
                                                    data-index={{ $color['id'] }}
                                                    style="background-color: {{ $color['color_code'] }};
                                                 ">
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div>


                                    <form id="customizationForm">
                                        @if (!empty($product->size))
                                            <div class="d-flex align-items-center mb-3">
                                                <label class="form-label me-2 mb-0" style="white-space: nowrap;">
                                                    @if ($product->type === 'ქეისი')
                                                        აირჩიეთ მოდელი
                                                    @elseif($product->type === 'მაისური')
                                                        აირჩიეთ ზომა
                                                    @else
                                                        {{ '' }}
                                                    @endif
                                                </label>
                                                <select id="sizeSelect" name="size" class="form-select">
                                                    @foreach (explode(',', $product->size) as $sizes)
                                                        <option value="{{ trim($sizes) }}">{{ trim($sizes) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </form>

                                </div>


                                <div class="d-flex align-items-center">
                                    <label class="form-label me-2 mb-0" style="white-space: nowrap;">რაოდენობა:</label>
                                    <div class="input-group" style="width: 140px;">
                                        <button type="button" class="btn btn-outline-secondary" id="decrement">-</button>
                                        <input type="number" name="quantity" id="quantity" value="1" min="1"
                                            class="form-control text-center">
                                        <button type="button" class="btn btn-outline-secondary" id="increment">+</button>
                                    </div>
                                </div>



                            </div>
                        </div>
                    @endif
                    </p>
                </div>

                <div id="uploader" class="tabcontent">

                    <p>
                    <form id="customizationForm">
                        <button type="button" id="toggleUploadSidebar" class="upload-btn" hidden>

                        </button>

                        <div>
                            <div class="upload-header">
                                <button id="closeUploadSidebar" class="close-btn" hidden>&times;</button>
                                <h4>ატვირთე </h4>
                            </div>
                            <input type="file" accept="image/*" id="uploaded_image" class="form-control">
                            <div id="imagePreviewContainer"></div>
                        </div> </form>
                        </p>
                </div>

                <div id="cliparts" class="tabcontent">
                    <div class="clipart-header">
                         <input type="text" id="searchCliparts" class="form-control" placeholder="🔍 კლიპარტების ძიება">
                        <select id="clipartCategory">
                            <option value="all">ყველა კატეგორია</option>
                            <option value="sport">სპორტი</option>
                            <option value="cars">მანქანები</option>
                            <option value="funny">სახალისო</option>
                            <option value="love">სასიყვარულო</option>
                            <option value="animation">ანიმაციური გმირები</option>
                            <option value="animals">ცხოველთა სამყარო</option>
                            <option value="emoji">ემოჯები</option>
                            <option value="tigerskin">ვეფხისტყაოსანი</option>
                            <option value="mamapapuri">მამაპაპური</option>
                            <option value="qatuli">ქართული თემა</option>
                        </select>
                    </div>
                    <div id="clipartContainer" class="row">
                        {{-- Cliparts will be loaded here via AJAX --}}
                    </div>

                    <div class="text-center mt-3">
                        <button id="loadMoreCliparts" class="btn btn-outline-primary">მეტის ნახვა</button>
                    </div>
                </div>

                <div id="text" class="tabcontent">
                    <p>
                        <!-- Text Customization Sidebar -->
                    <div class="side-modals" style="padding:5px !important; background-color:#ccc">

                        <div class="customization-boxs">
                             
                            <button type="button" id="addTextInput" class="btn btn-primary my-2">+ Add Text</button>
                            <div class="mb-3">
                                <label for="text_color" class="form-label">ტექსტის ფერი</label>
                                <input type="color" id="text_color" class="color-picker">
                            </div>


                            <div class="mb-3">
                                <label class="form-label">ტექსტის სტილი</label>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-dark text-style-btn" data-style="bold"
                                        title="Bold">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark text-style-btn"
                                        data-style="italic" title="Italic">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark text-style-btn"
                                        data-style="underline" title="Underline">
                                        <i class="fas fa-underline"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark text-style-btn"
                                        data-style="curved">
                                        <i class="fas fa-circle-notch"></i> <br> წრე
                                    </button>
                                    <button type="button" class="btn btn-outline-dark text-style-btn"
                                        data-style="normal" title="Reset">
                                        <i class="fas fa-undo"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="font_family" class="form-label">ფონტები</label>
                                <select id="font_family" class="form-control input-styled">
                                    <option value="Arial">Arial</option>
                                    <option value="Lobster-Regular">Lobster-Regular</option>
                                    <option value="Orbitron">Orbitron</option>
                                    <option value="Alk-rounded"
                                        style="font-family: 'alk-rounded', sans-serif !important;">
                                        <al> Alk-rounded </al>
                                    </option>
                                    <option value="PlaywriteIN"
                                        style="font-family: 'PlaywriteIN', sans-serif !important;">
                                        PlaywriteIN</option>
                                    <option value="Lobster-Regular"
                                        style="font-family: 'Lobster-Regular', sans-serif !important;">Lobster-Regular
                                    </option>
                                    <option value="Orbitron" style="font-family: 'Orbitron', sans-serif !important;">
                                        Orbitron
                                    </option>
                                    <option value="Orbitron">Orbitron</option>
                                    <option value="EricaOne" style="font-family: 'EricaOne', sans-serif !important;">
                                        EricaOne
                                    </option>
                                    <option value="GloriaHallelujah"
                                        style="font-family: 'GloriaHallelujah', sans-serif !important;">GloriaHallelujah
                                    </option>
                                    <option value="Creepster" style="font-family: 'Creepster', sans-serif !important;">
                                        Creepster</option>
                                    <option value="RubikBubbles"
                                        style="font-family: 'RubikBubbles', sans-serif !important;">
                                        RubikBubbles</option>
                                    <option value="BerkshireSwash"
                                        style="font-family: 'BerkshireSwash', sans-serif !important;">BerkshireSwash
                                    </option>
                                    <option value="Monoton" style="font-family: 'Monoton', sans-serif !important;">Monoton
                                    </option>
                                    <option value="BlackOpsOne"
                                        style="font-family: 'BlackOpsOne', sans-serif !important;">
                                        BlackOpsOne</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="font_size" class="form-label">ფონტის ზომა</label>
                                <input type="number" id="font_size" class="form-control input-styled" value="30"
                                    min="10" max="100">
                            </div>
                        </div>
                    </div>

                </div>
                </p>

                <div class="mb-4">
                    <label class="form-label d-block">გადიდება:</label>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-secondary" id="zoom-out">-</button>
                        <span id="zoom-level" class="mx-2">100%</span>
                        <button type="button" class="btn btn-outline-secondary" id="zoom-in">+</button>
                    </div>
                </div>

                <button id="addToCart" class="btn save-btn">დაამატე კალათაში</button>

                <a id="previewDesign" class="btn save-btn" style="display: none">Preview Design</a>
                </form>
            </div>

            <script>
                function openCity(evt, cityName) {
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

                // Get the element with id="defaultOpen" and click on it
                document.getElementById("defaultOpen").click();
            </script>




<script>
    let clipartOffset = 0;
const clipartLimit = 10;

// Event delegation — this catches clicks even on future images
document.getElementById("clipartContainer").addEventListener("click", function (e) {
    if (e.target && e.target.classList.contains("clipart-img")) {
        addClipArtToCanvas.call(e.target); // use your original function
    }
});

function loadCliparts() {
    axios.get('{{ route("cliparts.load") }}', {
        params: { offset: clipartOffset }
    }).then(response => {
        document.getElementById('clipartContainer').insertAdjacentHTML('beforeend', response.data.html);
        clipartOffset += clipartLimit;

        if (!response.data.hasMore) {
            document.getElementById('loadMoreCliparts').style.display = 'none';
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    loadCliparts(); // Load initial 10

    document.getElementById('loadMoreCliparts').addEventListener('click', function () {
        loadCliparts();
    });
});
</script>



            <div class="col-md-7 d-flex align-items-center justify-content-center"
                style="background-color: #f0f0f0;  position: relative;">
                <div id="design-area">
                    <img id="product-image" data-default-image="{{ asset('storage/' . $product->image1) }}"
                        src="{{ asset('storage/' . $product->image1) }}" alt="{{ $product->title }}"
                        data-id="{{ $product->id }}" style="width: 100%; height: auto; display: none;"
                        data-type={{ $product->type }}>

                    <canvas id="tshirtCanvas"></canvas>
                    <style>
                        .color-container {
                            display: flex;
                            align-items: center;
                            /* Aligns both sides vertically */
                            justify-content: center;
                            /* Keeps everything centered */
                            gap: 40px;
                            /* Space between sections */
                        }

                        .color-box,
                        .side-box {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            /* Center content */
                            text-align: center;
                        }

                        .label {
                            font-weight: bold;
                            margin-bottom: 5px;
                            /* Adds space between text and buttons */
                        }

                        .colors {
                            display: flex;
                            gap: 10px;
                            /* Space between color buttons */
                        }

                        .switch-buttons {
                            display: flex;
                            gap: 10px;
                            /* Space between Front/Back buttons */
                        }
                    </style>
                    <div class="color-selection" style="top: -100px !important; position: relative;">
                        <div class="color-container">



                            <!-- Side Selection (Right Side) -->


                            @php
                                $all_colors_have_front_and_back_images = true;

                                foreach ($productArray['colors'] as $color) {
                                    if (empty($color['front_image']) || empty($color['back_image'])) {
                                        $all_colors_have_front_and_back_images = false;
                                        break;
                                    }
                                }
                            @endphp

                            @if ($all_colors_have_front_and_back_images)
                                <div class="side-box">
                                    <p class="label">აირჩიეთ მხარე:</p>
                                    <div class="switch-buttons">

                                        <button id="showFront" class="btn btn-primary" data-image="">წინა</button>
                                        <button id="showBack" class="btn btn-secondary" data-image="">უკანა</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>

    <script>
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

                localStorage.setItem("quantity", qty); // Save to localStorage for main.js
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
    </script>




@endsection
