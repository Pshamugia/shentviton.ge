@props([
    'product' => null,
    'productArray' => [],
])


<div id="product" class="tabcontent">
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
                                data-front-index={{ 'front-' . $color['id'] }} data-index={{ $color['id'] }}>
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

                {{-- <button id="addToCart" class="d-block d-md-none btn save-btn">დაამატე კალათაში</button> --}}
            </div>
        </div>
    @endif
</div>
