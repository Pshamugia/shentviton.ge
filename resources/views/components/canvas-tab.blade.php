@props([
    'product' => null,
    'productArray' => [],
])

<div class="col-md-7 d-flex align-items-center justify-content-center" id="canvasContainer">
    <div id="design-area">
        <img id="product-image" data-default-image="{{ asset('storage/' . $product->image1) }}"
            src="{{ asset('storage/' . $product->image1) }}" alt="{{ $product->title }}" data-id="{{ $product->id }}"
            style="width: 100%; height: auto; display: none;" data-type="{{ $product->type }}">

        <canvas id="tshirtCanvas"></canvas>

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
                            <button id="showFront" class="btn btn-primary flex-grow-1 me-1" data-image="">წინა</button>
                            <button id="showBack" class="btn btn-secondary flex-grow-1 ms-1"
                                data-image="">უკანა</button>
                        </div>
                    </div>
                @endif
            </div>
            <button id="addToCartMobile" class="d-block d-lg-none small_save_btn">კალათაში დამატება</button>
        </div>
    </div>
</div>
