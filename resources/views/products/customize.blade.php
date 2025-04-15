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


    .tab {
        float: left;
        background-color: #272c33;
        width: 30%;
        height: 400px;
        scrollbar-color: red yellow;

    }

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


    .tab button:hover {
        background-color: #ddd;
    }

    .tab button.active {
        background-color: #ccc;
        margin-left: -20px;
    }

    .tabcontent {
        float: left;
        padding: 0px 12px;
        border: 1px solid #ccc;
        background-color: #ccc;
        width: 70%;
        border-left: none;
        height: 400px;
        overflow-y: auto;
        overflow-x: hidden;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .color-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 40px;
    }

    .color-box,
    .side-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .colors,
    .switch-buttons {
        display: flex;
        gap: 10px;
    }

    /* Add these styles to your existing CSS */
    @media screen and (min-width: 768px) and (max-width: 1199px) {
        #canvasWrapper {
            margin-top: 0;
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 500px;
            position: relative;
        }

        #tshirtCanvas {
            position: relative;
            top: 0;
            max-height: 100%;
            object-fit: contain;
        }

        .tabcontent {
            height: auto;
            min-height: 400px;
            max-height: 600px;
        }

        #canvasContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 2rem;
        }

        .color-selection {
            width: 100%;
        }
    }

    @media screen and (max-width: 1024px) {
        body.mobile-view {
            padding-bottom: 60px;
        }

        .tab {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60px;
            display: flex;
            flex-direction: row;
            overflow-x: auto;
            background-color: #272c33;
            z-index: 1000;
            border-bottom: none;
            border-top: 1px solid #444;
            justify-content: space-between;
        }

        .tab button {
            flex: 1;
            width: auto;
            height: 60px;
            text-align: center;
            padding: 8px 5px;
            color: white;
            border-bottom: none;
            border-top: 3px solid transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .tab button.active {
            background-color: #444;
            border-top: 3px solid yellow;
            margin-left: 0;
            color: yellow;
        }

        .tabcontent {
            width: 100%;
            max-width: 100%;
            height: calc(100vh - 60px);
            max-height: none;
            overflow-y: auto;
            border: none;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50;
            padding-top: 5rem;
            padding-bottom: 5rem;
        }

        #canvasWrapper {
            margin-top: 5rem;
            max-width: 500px;
        }

        .customization-container {
            padding: 0;
            max-width: 100%;
        }

        .customization-container .row {
            margin: 0;
        }

        .col-md-5,
        .col-md-7 {
            padding: 0;
        }
    }
</style>
<div class="container customization-container">

    <div class="row flex-column-reverse flex-md-row">
        <div class="col-md-5">

            {{-- TAB CONTROLS --}}
            <div class="tab">
                <button class="tablinks icon-color" onclick="openCity(event, 'product')" id="defaultOpen">
                    <i class="bi bi-clipboard-check-fill icon-color" style="font-size: 20px"></i> <br>
                    პროდუქტი
                </button>


                <button class="tablinks icon-color" onclick="openCity(event, 'uploader')" id="uploadBtn">
                    <i class="bi bi-card-image icon-color" style="font-size: 20px"></i> <br>
                    ატვირთე
                </button>

                <button class="tablinks icon-color" onclick="openCity(event, 'cliparts')" id="clipartBtn">
                    <i class="bi bi-palette icon-color" style="font-size: 20px"></i> <br>
                    კლიპარტი</button>
                <button class="tablinks icon-color" onclick="openCity(event, 'text')" id="textBtn">
                    <i class="bi bi-chat-square-quote-fill icon-color" style="font-size:20px"></i> <br>
                    ტექსტი</button>
            </div>

            {{-- PRODUCT --}}
            <div id="product" class="tabcontent">

                <p>
                <div style="text-align: right !important"> <label> <b> {{ $product->title }} </b>
                        <Br><span class="price-color" id="total-price"> {{ intval($product->price) }} ლარი </span>
                    </label>
                </div>

                @if (!empty($productArray['colors']) && count($productArray['colors']) > 0)

                    <!-- Color Selection (Left Side) -->
                    <div class="color-box" style="margin-top:50px;">
                        <div>
                            <label class="form-label text-start w-100">აირჩიეთ ფერი:</label>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach ($productArray['colors'] as $color)
                                    <div>
                                        <button class="color-option rounded-circle border border-secondary"
                                            style="width: 38px; height: 38px; background-color: {{ $color['color_code'] }};"
                                            data-color="{{ $color['color_code'] }}"
                                            data-front-image="{{ asset('storage/' . $color['front_image']) }}"
                                            data-back-image="{{ asset('storage/' . $color['back_image']) }}"
                                            data-back-index={{ 'back-' . $color['id'] }}
                                            data-front-index={{ 'front-' . $color['id'] }}
                                            data-index={{ $color['id'] }}>
                                        </button>
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

                            <button id="addToCart" class="d-block d-md-none btn save-btn">დაამატე კალათაში</button>
                        </div>
                    </div>
                @endif
                </p>
            </div>

            {{-- UPLOADER --}}
            <div id="uploader" class="tabcontent">

                <form id="customizationForm">
                    <button type="button" id="toggleUploadSidebar" class="upload-btn" hidden>

                    </button>

                    <form>
                        <div>
                            <div class="upload-header">
                                <button id="closeUploadSidebar" class="close-btn" hidden>&times;</button>
                                <h4>ატვირთე </h4>
                            </div>
                            <input type="file" accept="image/*" id="uploaded_image" class="form-control">
                            <div id="imagePreviewContainer"></div>
                        </div>
                    </form>
            </div>

            {{-- CLIPARTS --}}
            <div id="cliparts" class="tabcontent">
                <div class="clipart-header">
                    <input type="text" id="searchCliparts" class="chosen-select" placeholder="🔍 კლიპარტების ძიება">
                    <select id="clipartCategory" class="chosen-select" data-placeholder="აირჩიეთ კატეგორია">
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
                        <option value="qartuli">ქართული თემა</option>
                    </select>
                </div>
                <div id="clipartContainer" class="row">
                    {{-- Cliparts will be loaded here via AJAX --}}
                </div>

                <div class="text-center mt-3">
                    <button id="loadMoreCliparts" class="btn btn-outline-primary">მეტის ნახვა</button>
                </div>
                <Br>
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
            </script>

            {{-- TEXT --}}
            <div id="text" class="tabcontent">
                <p>
                    <!-- Text Customization Sidebar -->
                <div class="side-modals" style="padding:5px !important; background-color:#ccc">

                    <div class="customization-boxs">
                        <div id="textInputsContainer">
                            {{-- <div class="mb-3">
                                    <label for="top_text" class="form-label">ზედა ტექსტი</label>
                                    <input type="text" id="top_text" class="form-control input-styled"
                                        placeholder="Enter top text">
                                </div>
                                <div class="mb-3">
                                    <label for="bottom_text" class="form-label">ქვედა ტექსტი</label>
                                    <input type="text" id="bottom_text" class="form-control input-styled"
                                        placeholder="Enter bottom text">
                                </div> --}}

                        </div>
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
                            <select id="font_family" class="chosen-select" data-placeholder="აირჩიეთ ფონტი">
                                <option value=""></option>
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

            <script>
                //               $(document).ready(function () {
                //     $('#font_family').chosen({
                //         width: '100%',
                //         placeholder_text_single: "აირჩიეთ ფონტი"
                //     });

                //     function applyFont() {
                //         const selectedFont = $('#font_family').val();
                //         const $chosenSpan = $('#font_family').next('.chosen-container').find('.chosen-single span');

                //         $chosenSpan.attr('style', `font-family: "${selectedFont}" !important`);
                //     }

                //     $('#font_family').on('change', applyFont);
                //     $('#font_family').trigger('change');
                // });
            </script>
            </p>

            <div class="mb-4 d-none d-md-block">
                <label class="form-label ">გადიდება:</label>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" id="zoom-out">-</button>
                    <span id="zoom-level" class="mx-2">100%</span>
                    <button type="button" class="btn btn-outline-secondary" id="zoom-in">+</button>
                </div>
            </div>

            <button id="addToCart" class="d-none d-md-block btn save-btn">დაამატე კალათაში</button>

            </form>
        </div>

        <script>
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
        </script>




        <script>
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



        <div class="col-md-7 d-flex align-items-center justify-content-center" id="canvasContainer" style="">
            <div id="design-area" class="w-100 h-auto">
                <img id="product-image" data-default-image="{{ asset('storage/' . $product->image1) }}"
                    src="{{ asset('storage/' . $product->image1) }}" alt="{{ $product->title }}"
                    data-id="{{ $product->id }}" style="width: 100%; height: auto; display: none;"
                    data-type="{{ $product->type }}">

                <div class="d-flex align-items-center justify-content-center" id="canvasWrapper">
                    <canvas id="tshirtCanvas" style="max-width: 100%;"></canvas>
                </div>

                <div class="color-selection mt-3">
                    <div class="color-container">
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
                            <div class="side-box w-100">
                                <p class="label mb-1">აირჩიეთ მხარე:</p>
                                <div class="switch-buttons d-flex">
                                    <button id="showFront" class="btn btn-primary flex-grow-1 me-1"
                                        data-image="">წინა</button>
                                    <button id="showBack" class="btn btn-secondary flex-grow-1 ms-1"
                                        data-image="">უკანა</button>
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
